import { mountGrid } from './calendar-assemble';
import { gkToast } from './swal';

export async function refreshGrid(root) {
    if (!root?.dataset?.url) {
        return;
    }
    const start = window.gkLoadingStart;
    const stop = window.gkLoadingStop;
    start?.();
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
        stop?.();
    }
}
