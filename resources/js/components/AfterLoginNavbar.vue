<template>
        <div>
            <v-navigation-drawer app v-model="drawer" clipped color="white">
                <v-list class="pa-1">
                    <v-list-item>
                        <v-list-item-avatar>
                            <v-img :src="image"></v-img>
                        </v-list-item-avatar>
                        <v-list-item-content>
                            <v-list-item-title class="title">{{ username }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
                <v-list>
                    <Search @search="search($event)" />
                </v-list>
                <v-list class="pt-0" dense>
                    <v-divider></v-divider>
                    <v-list-item
                        v-for="item in userItems"
                        :key="item.title"
                        :to="item.to"
                        active-class="pink lighten-4"
                    >
                        <v-list-item-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item
                        :to="`/users/${user_id}/timeline`"
                        active-class="pink lighten-4"
                    >
                        <v-list-item-action>
                            <v-icon>dashboard</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>マイページ</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item @click="logout">
                        <v-list-item-action>
                            <v-icon>logout</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>ログアウト</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-navigation-drawer>
            <v-app-bar color="white" app dense clipped-left>
                <v-app-bar-nav-icon
                    @click = "drawer = !drawer"
                ></v-app-bar-nav-icon>
                <v-toolbar-title>
                    <RouterLink class="pink--text font-weight-bold text-decoration-none" to="/">
                        MessageShare
                    </RouterLink>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items class="hidden-sm-and-down">
                    <Search @search="search($event)" />
                    <v-btn
                        text to="/"
                        active-class="pink lighten-4">
                        <v-icon to="/">home</v-icon>
                            ホーム
                    </v-btn>
                    <v-menu
                        offset-y
                    >
                        <template v-slot:activator="{ attrs, on }">
                            <v-btn
                                color="white"
                                v-bind="attrs"
                                v-on="on"
                                elevation="0"
                            >
                                <v-badge
                                    v-if="hasNotification"
                                    bordered
                                    overlap
                                >
                                    <v-icon>notifications_none</v-icon>
                                </v-badge>
                                <v-icon v-else>notifications_none</v-icon>
                            </v-btn>
                        </template>
                        <v-list>
                            <v-list-item
                                v-for="notification in notifications"
                                :key="notification.message"
                                link :to="notification.to"
                                :class="{ grey: notification.read_at !== null }"
                            >
                                <v-list-item-title
                                    v-text="notification.message"
                                ></v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                    <v-btn
                        text :to="`/users/${user_id}/timeline`"
                        class="text-decoration-none"
                        active-class="pink lighten-4"
                    >
                        {{ username }}
                        <v-avatar size="30px">
                            <img :src="image">
                        </v-avatar>
                    </v-btn>
                </v-toolbar-items>
            </v-app-bar>
        </div>
</template>

<script>
import { mapState } from 'vuex'
import { makeNotificationMessage, routeNotification } from '../notifications.js'
import Search from '../components/NavSearch.vue'

export default {
    components: {
        Search,
    },
    data () {
        return {
            keyword: '',
            loginForm: {
                login_id: 'guest001',
                password: 'i29tg58f',
            },
            drawer: null,
            showSearchInput: false,
            userItems: [
                { title: "一覧", icon: "view_list", to: "/post"},
                { title: "募集する", icon: "question_answer", to: "/posting" },
            ],
            notifications: [],
            hasNotification: false,
        }
    },
    methods: {
        async fetchNotifications () {
            const response = await axios.get('/api/notifications')
            const notifications = response.data
            this.notifications = []
            this.hasNotification = false

            Array.from(notifications).forEach((notification, i) => {
                if (i > 0 && notifications[i].data.follower_id ===  notifications[i-1].data.follower_id) {
                    return false
                }
                this.notifications.push({
                    message: makeNotificationMessage(notification),
                    to: routeNotification(notification),
                    read_at: notification.read_at
                })
                if (notification.read_at === null) {
                    this.hasNotification = true
                }
            });
        },
        async search (params) {
            this.$router.push("/post/?page=1" + params)
                .catch(() => {})
        },
        async guestLogin () {
            await this.$store.dispatch('auth/login', this.loginForm)

            if (this.apiStatus) {
                this.$store.commit('message/setSuccessContent', {
                    successContent: 'ログインしました。',
                })
                this.$router.push('/post').catch(()=>{})
            }
        },
        clearError () {
            this.$store.commit('auth/setLoginErrorMessages', null)
        },
        async logout () {
            await this.$store.dispatch('auth/logout')

            this.$store.commit('message/setSuccessContent', {
                successContent: 'ログアウトしました。',
            })
            this.$router.push('/login')
        }
    },
    created () {
        this.clearError()
    },
    computed : {
        username () {
            return this.$store.getters['auth/username']
        },
        image () {
            return this.$store.getters['auth/image']
        },
        user_id () {
            return this.$store.getters['auth/id']
        },
        ...mapState({
            apiStatus: state => state.auth.apiStatus,
            loginErrors: state => state.auth.loginErrorMessages
        })
    },
    watch: {
        $route: {
            async handler () {
                await this.fetchNotifications()
            },
            immediate: true
        }
    }
}
</script>