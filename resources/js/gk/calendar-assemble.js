import { attachDragScroll } from './calendar-drag-scroll';
import { headRow } from './calendar-table-head';
import { bodyRows } from './calendar-table-body';
import { footRows } from './calendar-table-foot';
import { bindCells } from './calendar-wire';

export function mountGrid(root, payload) {
    const tbl = document.createElement('table');
    tbl.className = 'gk-table';
    const thead = document.createElement('thead');
    thead.appendChild(headRow(payload.days, payload.today_day ?? null));
    const tbody = document.createElement('tbody');
    tbody.appendChild(bodyRows(payload));
    const tfoot = document.createElement('tfoot');
    tfoot.appendChild(footRows(payload));
    tbl.appendChild(thead);
    tbl.appendChild(tbody);
    tbl.appendChild(tfoot);
    root.innerHTML = '';
    const wrap = document.createElement('div');
    wrap.className = 'gk-scroll';
    wrap.appendChild(tbl);
    root.appendChild(wrap);
    attachDragScroll(wrap);
    bindCells(wrap, payload, root.dataset.store, root.dataset.entries);
}
