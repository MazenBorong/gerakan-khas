import { gkConfirm, gkPromptConfirm, gkToast } from './swal';

document.addEventListener('livewire:init', () => {
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
