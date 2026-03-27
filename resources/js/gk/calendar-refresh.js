import { mountGrid } from './calendar-assemble';
import { gkToast } from './swal';

export async function refreshGrid(root) {
    if (!root?.dataset?.url) {
        return;
    }
    const loadingEl = document.getElementById('gk-cal-root-loading');
    loadingEl?.classList.remove('gk-cal-root__loading--hidden');
    loadingEl?.setAttribute('aria-hidden', 'false');
    try {
        const { data } = await window.axios.get(root.dataset.url, { gkSkipLoading: true });
        mountGrid(root, data);
    } catch (e) {
        gkToast(
            'error',
            'Calendar could not load',
            e?.response?.data?.message || e?.message || 'Try refreshing the page.',
        );
    } finally {
        loadingEl?.classList.add('gk-cal-root__loading--hidden');
        loadingEl?.setAttribute('aria-hidden', 'true');
    }
}
