import './bootstrap'
import Vue from 'vue'
import router from './router'
import store from './store'
import Croppa from 'vue-croppa'
import 'vue-croppa/dist/vue-croppa.css'
import App from './App.vue'

const createApp = async () => {
  await store.dispatch('auth/currentUser')

  Vue.use(Croppa)

  new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App />'
  })
}
createApp()