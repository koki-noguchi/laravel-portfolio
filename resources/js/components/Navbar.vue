<template>
        <div>
            <v-navigation-drawer app v-model="drawer" clipped>
                <v-list>
                    <Search @search="search($event)" />
                </v-list>
                <v-list class="pt-0" dense>
                    <v-divider></v-divider>
                    <v-list-item @click.prevent="guestLogin">
                        <v-list-item-action>
                            <v-icon>verified_user</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>ゲストログイン</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item
                        v-for="item in items"
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
                </v-list>
            </v-navigation-drawer>
            <v-app-bar color="white" app dense clipped-left>
                <v-app-bar-nav-icon
                    @click.stop="drawer = !drawer"
                ></v-app-bar-nav-icon>
                <v-toolbar-title>
                    <RouterLink class="pink--text font-weight-bold text-decoration-none" to="/">
                        MessageShare
                    </RouterLink>
                </v-toolbar-title>
                <v-spacer></v-spacer>
            </v-app-bar>
        </div>
</template>

<script>
import { mapState } from 'vuex'
import Search from '../components/NavSearch.vue'

export default {
    components: {
        Search
    },
    data () {
        return {
            posts: [],
            loginForm: {
                login_id: 'guest001',
                password: 'i29tg58f',
            },
            drawer: null,
            showSearchInput: false,
            items: [
                { title: "ホーム", icon: "home", to: "/" },
                { title: "登録", icon: "how_to_reg", to: "/register" },
                { title: "ログイン", icon: "account_box", to: "/login" },
            ],
        }
    },
    methods: {
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
        ...mapState({
            apiStatus: state => state.auth.apiStatus,
            loginErrors: state => state.auth.loginErrorMessages
        })
    }
}
</script>