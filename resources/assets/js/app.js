
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

//Libreria que me permite realizar conexiones ajax
import axios from 'axios'

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('usuario-component',require('./components/UsuarioComponent.vue'));
Vue.component('colectivo', require('./components/Colectivo.vue'));
Vue.component('parada', require('./components/Parada.vue'));
Vue.component('tarifa', require('./components/Tarifa.vue'));
Vue.component('horario', require('./components/Horario.vue'));
Vue.component('recorrido', require('./components/Recorrido.vue'));
Vue.component('tramo', require('./components/Tramo.vue'));
Vue.component('punto', require('./components/PuntoRecarga.vue'));
Vue.component('comentario', require('./components/Comentario.vue'));
Vue.component('sugerencia', require('./components/Sugerencia.vue'));
Vue.component('mapa', require('./components/Mapa.vue'));

const app = new Vue({
    el: '#app'
});
