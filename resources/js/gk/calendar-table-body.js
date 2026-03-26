function cellClass(meta, canEdit) {
    const parts = ['gk-cell'];
    if (meta.weekend || meta.holiday) {
        parts.push('gk-cell--off', 'gk-cell--nohit');
    } else if (canEdit) {
        parts.push('gk-cell--hit');
    } else {
        parts.push('gk-cell--nohit');
    }
    return parts.join(' ');
}

function pillClass(code) {
    const map = { wfh: 'gk-cell-pill--wfh', leave: 'gk-cell-pill--leave', sl: 'gk-cell-pill--sl', last_day: 'gk-cell-pill--last' };
    return map[code] || '';
}

export function bodyRows(p) {
    const f = document.createDocumentFragment();
    p.users.forEach((u) => {
        const tr = document.createElement('tr');
        const nm = document.createElement('td');
        nm.className = 'gk-td gk-sticky-col gk-name';
        nm.textContent = u.name;
        tr.appendChild(nm);
        p.days.forEach((d) => {
            const code = p.matrix[`${u.id}-${d.day}`] || '';
            const canEdit =
                !d.weekend &&
                !d.holiday &&
                (p.role !== 'staff' || Number(u.id) === Number(p.actor_id));
            const td = document.createElement('td');
            td.className = `gk-td ${cellClass(d, canEdit)}`;
            if (code) {
                const span = document.createElement('span');
                span.className = `gk-cell-pill ${pillClass(code)}`.trim();
                span.textContent = p.statuses[code] || code;
                td.appendChild(span);
            }
            td.dataset.u = String(u.id);
            td.dataset.d = String(d.day);
            tr.appendChild(td);
        });
        f.appendChild(tr);
    });
    return f;
}
