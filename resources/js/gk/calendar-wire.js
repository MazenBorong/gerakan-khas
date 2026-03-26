import { gkPickStatus, gkToast } from './swal';

function planEntryErrorMessage(e, p) {
    const raw = e?.response?.data?.message;
    if (typeof raw !== 'string' || !raw.trim()) {
        return 'Something went wrong. Please try again.';
    }
    const key = raw.trim();
    if (key === 'too_soon') {
        const lead = Number(p?.rules?.lead_days_ahead);
        const first = p?.rules?.first_bookable_on_or_after;
        if (Number.isFinite(lead) && lead >= 0 && typeof first === 'string' && first.length >= 10) {
            return `That date is still inside the advance-notice window. Staff must choose ${first} or a later day (minimum ${lead} calendar days ahead of today — see Settings).`;
        }
        return 'That date is still inside the advance-notice window for staff. Pick a later day or ask an admin to adjust lead time in Settings.';
    }
    const map = {
        non_working_day: 'That day is not a working day.',
        past_date: 'That date is in the past.',
        wfh_full: 'WFH capacity for that day is full.',
    };
    return map[key] || key;
}

export function bindCells(scope, p, store, base) {
    scope.querySelectorAll('td.gk-cell--hit[data-d]').forEach((td) => {
        td.addEventListener('click', async () => {
            const uid = Number(td.dataset.u);
            const day = Number(td.dataset.d);
            const key = `${uid}-${day}`;
            const opts = { clear: 'Clear', ...p.statuses };
            const r = await gkPickStatus(opts);
            if (r.isDismissed) {
                return;
            }
            const next = r.value === 'clear' ? '' : r.value;
            const payload = { year: p.year, month: p.month, day, user_id: uid, status: next };
            try {
                const id = p.entryIds?.[key];
                if (id) {
                    await window.axios.patch(`${base}/${id}`, payload);
                } else {
                    await window.axios.post(store, payload);
                }
                gkToast('success', 'Calendar updated', 'Your change was saved.');
                const { refreshGrid } = await import('./calendar-refresh.js');
                await refreshGrid(document.getElementById('gk-root'));
            } catch (e) {
                gkToast('error', 'Could not save', planEntryErrorMessage(e, p));
            }
        });
    });
}
