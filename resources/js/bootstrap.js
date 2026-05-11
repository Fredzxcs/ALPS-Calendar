import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Set CSRF token for axios
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) {
	window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
}

// Set CSRF token for jQuery AJAX
if (window.jQuery) {
	window.jQuery.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': token
		}
	});
}
