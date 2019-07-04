try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

import Vue from 'vue'

import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)
