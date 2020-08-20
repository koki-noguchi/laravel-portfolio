import Vue from 'vue'
import VueRouter from 'vue-router'

import Index from './pages/Index.vue'
import Register from './pages/register.vue'
import Login from './pages/Login.vue'
import Post from './pages/Posting.vue'
import PostEdit from './pages/PostEdit.vue'
import PostList from './pages/PostList.vue'
import PostDetail from './pages/PostDetail.vue'
import MessageDetail from './pages/MessageDetail.vue'
import UserProfile from './pages/UserProfile.vue'
import FollowList from './pages/FollowList.vue'
import FollowerList from './pages/FollowerList.vue'
import ServiceDescription from './pages/FeatureDescription.vue'
import NotFound from './pages/errors/NotFound.vue'
import Timeline from './components/Timeline.vue'
import History from './components/History.vue'
import Bookmark from './components/Bookmark.vue'

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
        path: '/users/:id',
        component: UserProfile,
        props: true,
        children: [
            {
                path: 'timeline',
                component: Timeline,
                beforeEnter (to, from, next) {
                    if (to.params.id === String(store.getters['auth/id'])) {
                        next()
                    } else {
                        next({ path: `/users/${to.params.id}/history` })
                    }
                }
            },
            {
                path: 'history',
                component: History,
                props: true
            },
            {
                path: 'bookmark',
                component: Bookmark,
                beforeEnter (to, from, next) {
                    if (to.params.id === String(store.getters['auth/id'])) {
                        next()
                    } else {
                        next({ path: `/users/${to.params.id}/history` })
                    }
                }
            }
        ]
    },
    {
        path: '/users/:id/follow',
        component: FollowList,
        props: true
    },
    {
        path: '/users/:id/follower',
        component: FollowerList,
        props: true
    },
    {
        path: '/about',
        component: ServiceDescription,
    },
    {
        path: '*',
        component: NotFound
    },
    {
        path: '/500',
        component: SystemError
    },
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