<template>
        <div>
            <v-navigation-drawer app v-model="drawer" clipped>
                <v-list>
                    <v-text-field
                        @keydown.enter.prevent="search"
                        v-model="keyword"
                        clearable
                        flat
                        label="Search"
                        prepend-inner-icon="search"
                        solo
                        single-line
                        hide-details
                    ></v-text-field>
                </v-list>
                <v-list class="pt-0" dense>
                    <v-divider></v-divider>
                    <v-list-item @click.prevent="guestLogin">
                        <v-list-item-action>
                            <v-icon>verified_user</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Guest Login</v-list-item-title>
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
            <v-app-bar color="transparent" app dense clipped-left>
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

export default {
    data () {
        return {
            keyword: '',
            posts: [],
            loginForm: {
                login_id: 'guest001',
                password: 'i29tg58f',
            },
            drawer: null,
            showSearchInput: false,
            items: [
                { title: "Home", icon: "home", to: "/" },
                { title: "Register", icon: "how_to_reg", to: "/register" },
                { title: "Login", icon: "account_box", to: "/login" },
            ]
        }
    },
    methods: {
        async search () {
            var string = location.href
            var pattern = '/post?keyword=' + this.keyword
            if ((string.lastIndexOf(pattern)+pattern.length===string.length)&&(pattern.length<=string.length)) {
                return false
            } else {
                await axios.get('/api/post?keyword=' + this.keyword)
                .then(response => this.posts = response.data)
                .catch(error => {})
                this.$router.push('/post?keyword=' + this.keyword)
            }
        },
        async guestLogin () {
            await this.$store.dispatch('auth/login', this.loginForm)

            if (this.apiStatus) {
                this.$store.commit('message/setSuccessContent', {
                    successContent: 'ログインしました。',
                })
                this.$router.push('/').catch(()=>{})
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