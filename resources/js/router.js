import Vue from 'vue'
import VueRouter from 'vue-router'

import Index from './pages/Index.vue'
import Register from './pages/register.vue'
import Login from './pages/Login.vue'
import Post from './pages/Posting.vue'
import PostEdit from './pages/PostEdit.vue'
import PostList from './pages/PostList.vue'
import PostDetail from './pages/PostDetail.vue'
import Mypage from './pages/Mypage.vue'
import MessageDetail from './pages/MessageDetail.vue'

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
        path: '/posting',
        component: Post,
        beforeEnter (to, from, next) {
            if (store.getters['auth/check']) {
                next()
            } else {
                next('/')
            }
        }
    },
    {
        path: '/post',
        component: PostList
    },
    {
        path: '/post?:keyword',
        component: PostList,
        props: true
    },
    {
        path: '/mypage',
        component: Mypage,
        beforeEnter (to, from, next) {
            if (store.getters['auth/check']) {
                next()
            } else {
                next('/')
            }
        }
    },
    {
        path: '/post/:id',
        component: PostDetail,
        props: true
    },
    {
        path: '/post/:id/edit',
        component: PostEdit,
        props: true
    },
    {
        path: '/post/:post_id/message/:message_id',
        component: MessageDetail,
        props: true
    },
    {
        path: '/500',
        component: SystemError
    }
]

const router = new VueRouter({
    mode: 'history',
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { x: 0, y: 0 };
        }
    }
})

export default router