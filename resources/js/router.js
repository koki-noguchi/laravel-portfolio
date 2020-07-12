import Vue from 'vue'
import VueRouter from 'vue-router'

import Index from './pages/Index.vue'
import Register from './pages/register.vue'
import Login from './pages/Login.vue'

import store from './store'

import SystemError from './pages/errors/System.vue'

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        component: Index
    },
    {
        path: '/register',
        component: Register,
        beforeEnter (to, from, next) {
            if (store.getters['auth/check']) {
                next('/')
            } else {
                next()
            }
        }
    },
    {
        path: '/login',
        component: Login,
        beforeEnter (to, from, next) {
            if (store.getters['auth/check']) {
                next('/')
            } else {
                next()
            }
        }
    },
    {
        path: '/500',
        component: SystemError
    }
]

const router = new VueRouter({
    mode: 'history',
    routes
})

export default router