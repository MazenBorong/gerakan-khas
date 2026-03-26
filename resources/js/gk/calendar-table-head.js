export function headRow(days, todayDay) {
    const tr = document.createElement('tr');
    const corner = document.createElement('th');
    corner.className = 'gk-th gk-sticky-col gk-cal-corner';
    corner.setAttribute('aria-hidden', 'true');
    tr.appendChild(corner);
    days.forEach((d) => {
        const th = document.createElement('th');
        const off = d.weekend || d.holiday;
        const isToday = todayDay != null && d.day === todayDay;
        th.className = 'gk-th gk-cal-th' + (off ? ' gk-th--off' : '') + (isToday ? ' gk-th--today' : '');
        const dow = document.createElement('span');
        dow.className = 'gk-cal-dow';
        dow.textContent = d.weekday || '';
        const dom = document.createElement('span');
        dom.className = 'gk-cal-dom';
        dom.textContent = String(d.day);
        th.appendChild(dow);
        th.appendChild(dom);
        if (d.holiday) {
            th.title = d.holiday;
        }
        tr.appendChild(th);
    });
    return tr;
}
