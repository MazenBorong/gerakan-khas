import { gkConfirm, gkPromptConfirm, gkToast } from './swal';

function gkLoadingOverlayEl() {
    return document.getElementById('gk-loading-overlay');
}

function gkLoadingInit() {
    let depth = 0;
    const el = gkLoadingOverlayEl();
    if (!el) {
        return;
    }
    const show = () => {
        depth += 1;
        if (depth === 1) {
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

    Livewire.interceptRequest(({ onSend, onFinish }) => {
        onSend(show);
        onFinish(hide);
    });
}

document.addEventListener('livewire:init', () => {
    gkLoadingInit();
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
