<template>
        <div>
            <v-navigation-drawer v-model="drawer" absolute temporary>
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
            <v-app-bar color="transparent" dense>
                <v-app-bar-nav-icon
                    @click.stop="drawer = !drawer"
                    class="hidden-md-and-up"
                ></v-app-bar-nav-icon>
                <v-toolbar-title>
                    <RouterLink style="text-decoration: none;" class="pink--text font-weight-bold" to="/">
                        MessageShare
                    </RouterLink>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items class="hidden-sm-and-down">
                    <v-icon class="mt-3">search</v-icon>
                    <v-text-field
                    @keydown.enter.prevent="search"
                    v-model="keyword"
                    placeholder="id or title"
                    clearable
                    dense
                    class="mt-3 shrink"
                    >
                    </v-text-field>
                    <v-btn text @click.prevent="guestLogin">
                        <v-icon @click.prevent="guestLogin">verified_user</v-icon>
                            ゲストログイン
                    </v-btn>
                    <v-btn text to="/">
                        <v-icon to="/">home</v-icon>
                            ホーム
                    </v-btn>
                    <v-btn text to="/register">
                        <v-icon to="/register">how_to_reg</v-icon>
                            新規登録
                    </v-btn>
                    <v-btn text to="/login">
                        <v-icon to="/login">account_box</v-icon>
                            ログイン
                    </v-btn>
                </v-toolbar-items>
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
                { title: "Home", icon: "home", to: "/", event: '' },
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