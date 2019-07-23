require('./bootstrap');

window.Vue = require('vue');

const files = require.context('./components', false, /\.vue$/i);
files.keys().map(key => {
    let componentName = key.split('/').pop().split('.')[0];
    Vue.component(componentName, () => import(
        /* webpackChunkName: "[request]" */
        `./components/${componentName}`
    ))
});

import store from '@js/store'

const app = new Vue({
    el: '#app',
    store,
});
