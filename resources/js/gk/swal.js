import Swal from 'sweetalert2';

/** Auto-dismiss delay for all `gkToast` notifications (SweetAlert2 toast mode). */
const GK_SWAL_TOAST_TIMER_MS = 10000;

const gkDialogDefaults = () => ({
    customClass: {
        popup: 'gk-swal-popup',
        container: 'gk-swal-container',
        confirmButton: 'gk-swal-btn gk-swal-btn--confirm',
        cancelButton: 'gk-swal-btn gk-swal-btn--cancel',
        denyButton: 'gk-swal-btn gk-swal-btn--deny',
        title: 'gk-swal-title',
        htmlContainer: 'gk-swal-body',
        input: 'gk-swal-input',
        validationMessage: 'gk-swal-validation',
    },
    buttonsStyling: false,
    width: '26rem',
    padding: '1.25rem',
    backdrop: true,
});

const GkDialog = Swal.mixin(gkDialogDefaults());

const GkToast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: GK_SWAL_TOAST_TIMER_MS,
    timerProgressBar: true,
    customClass: {
        popup: 'gk-swal-toast-popup',
        container: 'gk-swal-toast-container',
    },
    buttonsStyling: false,
});

/**
 * @param {string} message
 * @param {{ title?: string, icon?: import('sweetalert2').SweetAlertIcon, confirmButtonText?: string, cancelButtonText?: string }} [opts]
 * @returns {Promise<boolean>}
 */
export function gkConfirm(message, opts = {}) {
    const title = opts.title ?? 'Are you sure?';
    const icon = opts.icon ?? 'question';
    return GkDialog.fire({
        icon,
        title,
        text: message,
        showCancelButton: true,
        confirmButtonText: opts.confirmButtonText ?? 'Continue',
        cancelButtonText: opts.cancelButtonText ?? 'Cancel',
        focusCancel: true,
    }).then((r) => r.isConfirmed);
}

/**
 * @param {string} question
 * @param {string} expected
 * @returns {Promise<boolean>}
 */
export function gkPromptConfirm(question, expected) {
    return GkDialog.fire({
        icon: 'warning',
        title: 'Confirmation required',
        text: question,
        input: 'text',
        inputPlaceholder: expected,
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
        focusCancel: true,
        preConfirm: (value) => {
            if (value !== expected) {
                Swal.showValidationMessage(`Type "${expected}" exactly to confirm.`);
                return false;
            }
            return true;
        },
    }).then((r) => r.isConfirmed);
}

/**
 * Toast copy uses titleText + text (not title) so SweetAlert2 does not run the
 * headline through HTML parsing (see parseHtmlToContainer / DOMParser).
 *
 * @param {import('sweetalert2').SweetAlertIcon} icon
 * @param {string} headline Plain-text heading
 * @param {string} [detail] Optional plain-text line below the heading
 */
export function gkToast(icon, headline, detail) {
    const opts = {
        icon,
        titleText: headline,
    };
    if (detail) {
        opts.text = detail;
    }
    GkToast.fire(opts);
}

export function gkPickStatus(inputOptions) {
    return GkDialog.fire({
        title: 'Set day status',
        input: 'select',
        inputOptions,
        inputPlaceholder: 'Choose status',
        showCancelButton: true,
        confirmButtonText: 'Apply',
        cancelButtonText: 'Cancel',
        focusCancel: true,
    });
}
