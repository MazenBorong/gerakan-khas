import { gkConfirm, gkPromptConfirm, gkToast } from './swal';

function gkLoadingOverlayEl() {
    return document.getElementById('gk-loading-overlay');
}

function gkLoadingInit() {
    const el = gkLoadingOverlayEl();
    if (!el) {
        return {
            show: () => {},
            hide: () => {},
        };
    }

    let depth = el.classList.contains('is-active') ? 1 : 0;
    const show = () => {
        depth += 1;
        if (depth > 0) {
            el.classList.add('is-active');
            el.setAttribute('aria-hidden', 'false');
        }
    };
    const hide = () => {
        depth = Math.max(0, depth - 1);
        if (depth === 0) {
            el.classList.remove('is-active');
            el.setAttribute('aria-hidden', 'true');
        }
    };

    const sameOrigin = (url) => {
        try {
            return new URL(url, window.location.href).origin === window.location.origin;
        } catch {
            return false;
        }
    };

    const isNavigationClick = (event) => {
        if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return false;
        }
        const anchor = event.target.closest('a[href]');
        if (!anchor) {
            return false;
        }
        if (anchor.target && anchor.target !== '_self') {
            return false;
        }
        if (!sameOrigin(anchor.href)) {
            return false;
        }
        const next = new URL(anchor.href, window.location.href);
        const current = new URL(window.location.href);
        if (next.pathname === current.pathname && next.search === current.search && next.hash !== current.hash) {
            return false;
        }
        return true;
    };

    const installAxiosLoading = () => {
        if (!window.axios || window.axios.__gkLoadingInstalled) {
            return;
        }
        window.axios.__gkLoadingInstalled = true;
        window.axios.interceptors.request.use(
            (config) => {
                if (!config.gkSkipLoading) {
                    show();
                }
                return config;
            },
            (error) => {
                hide();
                return Promise.reject(error);
            },
        );
        window.axios.interceptors.response.use(
            (response) => {
                if (!response.config?.gkSkipLoading) {
                    hide();
                }
                return response;
            },
            (error) => {
                if (!error.config?.gkSkipLoading) {
                    hide();
                }
                return Promise.reject(error);
            },
        );
    };

    const installFetchLoading = () => {
        if (!window.fetch || window.__gkFetchLoadingInstalled) {
            return;
        }
        window.__gkFetchLoadingInstalled = true;
        const nativeFetch = window.fetch.bind(window);
        window.fetch = (...args) => {
            show();
            return nativeFetch(...args).finally(() => {
                hide();
            });
        };
    };

    const isLivewireManagedForm = (form) => {
        if (!form) {
            return false;
        }
        return Array.from(form.attributes).some((attr) => attr.name.startsWith('wire:submit'));
    };

    window.gkLoadingStart = show;
    window.gkLoadingStop = hide;

    installAxiosLoading();
    installFetchLoading();

    document.addEventListener('click', (event) => {
        if (isNavigationClick(event)) {
            show();
        }
    }, true);

    document.addEventListener('submit', (event) => {
        if (isLivewireManagedForm(event.target)) {
            return;
        }
        show();
    }, true);

    window.addEventListener('livewire:navigate', show);
    window.addEventListener('livewire:navigated', hide);
    window.addEventListener('pageshow', hide);

    requestAnimationFrame(() => hide());

    return { show, hide };
}

// Install overlay + Axios/fetch/navigation hooks as soon as this module runs (before DOMContentLoaded
// handlers like calendar-boot). If this only ran on `livewire:init`, the calendar could fetch before
// interceptors existed and no spinner would show.
const { show: gkLoadingShow, hide: gkLoadingHide } = gkLoadingInit();

function gkIsLoginPage() {
    return Boolean(document.querySelector('[data-gk-login-form]'));
}

document.addEventListener('livewire:init', () => {
    Livewire.interceptRequest(({ onSend, onFinish, onRedirect }) => {
        onSend(() => {
            if (!gkIsLoginPage()) {
                gkLoadingShow();
            }
        });
        onFinish(() => {
            if (!gkIsLoginPage()) {
                gkLoadingHide();
            }
        });
        onRedirect(() => {
            if (!gkIsLoginPage()) {
                gkLoadingShow();
            }
        });
    });
    Livewire.on('gk-toast', (payload) => {
        if (!payload || typeof payload !== 'object') {
            return;
        }
        const icon = payload.icon ?? 'success';
        const headline = payload.headline ?? '';
        const detail = payload.detail;
        if (headline) {
            gkToast(icon, headline, detail ? String(detail) : undefined);
        }
    });
    Livewire.hook('directive.init', ({ el, directive }) => {
        if (directive.value !== 'confirm') {
            return;
        }
        let message = directive.expression.replaceAll('\\n', '\n');
        if (message === '') {
            message = 'Are you sure?';
        }
        const shouldPrompt = directive.modifiers.includes('prompt');
        el.__livewire_confirm = (action, instead) => {
            if (shouldPrompt) {
                const [question, expected] = message.split('|');
                if (!expected) {
                    console.warn('Livewire: Must provide expectation with wire:confirm.prompt');
                    instead();
                    return;
                }
                gkPromptConfirm(question.trim(), expected.trim()).then((ok) => {
                    if (ok) {
                        action();
                    } else {
                        instead();
                    }
                });
            } else {
                gkConfirm(message, {
                    title: 'Are you sure?',
                    icon: 'warning',
                    confirmButtonText: 'Yes, continue',
                    cancelButtonText: 'Cancel',
                }).then((ok) => {
                    if (ok) {
                        action();
                    } else {
                        instead();
                    }
                });
            }
        };
    });

    Livewire.interceptRequest(({ onError }) => {
        onError(({ response, preventDefault }) => {
            if (response.status !== 419) {
                return;
            }
            preventDefault();
            gkConfirm('Your session expired. Refresh the page to continue.', {
                title: 'Session expired',
                icon: 'warning',
                confirmButtonText: 'Refresh page',
                cancelButtonText: 'Stay here',
            }).then((ok) => {
                if (ok) {
                    window.location.reload();
                }
            });
        });
    });
});
