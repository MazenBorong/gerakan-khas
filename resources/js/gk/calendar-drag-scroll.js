export function attachDragScroll(el) {
    let active = false;
    let lastX = 0;
    const end = (e) => {
        if (!active) {
            return;
        }
        active = false;
        el.classList.remove('gk-scroll--dragging');
        try {
            el.releasePointerCapture(e.pointerId);
        } catch (_) {
            //
        }
    };
    el.addEventListener('pointerdown', (e) => {
        if (e.button !== 0 || e.target.closest('.gk-cell--hit, a, button')) {
            return;
        }
        active = true;
        lastX = e.clientX;
        el.classList.add('gk-scroll--dragging');
        el.setPointerCapture(e.pointerId);
    });
    el.addEventListener('pointermove', (e) => {
        if (!active) {
            return;
        }
        el.scrollLeft -= e.clientX - lastX;
        lastX = e.clientX;
    });
    el.addEventListener('pointerup', end);
    el.addEventListener('pointercancel', end);
    el.addEventListener(
        'wheel',
        (e) => {
            if (!e.shiftKey) {
                return;
            }
            el.scrollLeft += e.deltaY;
            e.preventDefault();
        },
        { passive: false },
    );
}
