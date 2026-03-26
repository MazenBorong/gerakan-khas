import { refreshGrid } from './calendar-refresh';

export async function bootCalendar() {
    const root = document.getElementById('gk-root');
    if (!root) {
        return;
    }
    await refreshGrid(root);
}

function scheduleBoot() {
    queueMicrotask(() => bootCalendar());
}

document.addEventListener('DOMContentLoaded', scheduleBoot);
document.addEventListener('livewire:navigated', scheduleBoot);
