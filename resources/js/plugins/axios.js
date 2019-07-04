import Vue from 'vue'
import axios from 'axios'

let token = document.head.querySelector('meta[name="csrf-token"]');

if (!token) {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

let baseAxios = axios.create({
    baseURL: '/test',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': token ? token.content : undefined,
    },
});

let apiAxios = axios.create({
    baseURL: '/api',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': token ? token.content : undefined,
    }
});

Vue.prototype.$axios = baseAxios;

export { baseAxios, apiAxios }
