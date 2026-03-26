export function footRows(p) {
    const f = document.createDocumentFragment();
    const wfh = document.createElement('tr');
    const w0 = document.createElement('td');
    w0.className = 'gk-td gk-sticky-col gk-sum gk-sum--wfh';
    w0.textContent = 'WFH';
    wfh.appendChild(w0);
    const leave = document.createElement('tr');
    const l0 = document.createElement('td');
    l0.className = 'gk-td gk-sticky-col gk-sum gk-sum--leave';
    l0.textContent = 'On Leave';
    leave.appendChild(l0);
    p.days.forEach((d) => {
        const a = document.createElement('td');
        a.className = 'gk-td gk-sum-val gk-sum-val--wfh';
        a.textContent = String(p.wfh?.[d.day] ?? 0);
        wfh.appendChild(a);
        const b = document.createElement('td');
        b.className = 'gk-td gk-sum-val gk-sum-val--leave';
        b.textContent = String(p.leave?.[d.day] ?? 0);
        leave.appendChild(b);
    });
    f.appendChild(wfh);
    f.appendChild(leave);
    return f;
}
