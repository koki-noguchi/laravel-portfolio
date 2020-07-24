import './bootstrap'
import Vue from 'vue'
import router from './router'
import store from './store'
import Croppa from 'vue-croppa'
import 'vue-croppa/dist/vue-croppa.css'
import '@mdi/font/css/materialdesignicons.css';
import 'material-design-icons-iconfont/dist/material-design-icons.css';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import App from './App.vue'

Vue.use(Vuetify);
Vue.use(Croppa);
const createApp = async () => {
  await store.dispatch('auth/currentUser')

  new Vue({
  vuetify: new Vuetify(),
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App />'
  })
}
createApp()