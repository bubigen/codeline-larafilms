
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VeeValidate from 'vee-validate';
import VueResource from 'vue-resource';
import VuePaginator from 'vuejs-paginator';
import Hub from './events/Hub';

// Uses
Vue.use(VeeValidate);
Vue.use(VueResource)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example', require('./components/Example.vue'));
Vue.component('film-form', require('./components/FilmForm.vue'));
Vue.component('films', require('./components/Film.vue'));

const app = new Vue({
    el: '#app'
});
