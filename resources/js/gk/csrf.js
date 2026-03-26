const m = document.querySelector('meta[name="csrf-token"]');
if (m && window.axios) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = m.getAttribute('content');
    window.axios.defaults.headers.common['Accept'] = 'application/json';
}
