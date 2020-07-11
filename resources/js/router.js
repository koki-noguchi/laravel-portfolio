import Vue from 'vue'
import VueRouter from 'vue-router'

import Index from './pages/Index.vue'
import Register from './pages/register.vue'
import Login from './pages/Login.vue'

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        component: Index
    },
    {
        path: '/register',
        component: Register
    },
    {
        path: '/login',
        component: Login
    }
]

const router = new VueRouter({
    mode: 'history',
    routes
})

export default router