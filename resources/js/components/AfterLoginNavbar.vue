<template>
        <div>
            <v-navigation-drawer app v-model="drawer" clipped>
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
                    <v-list-item @click="logout">
                        <v-list-item-action>
                            <v-icon>logout</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Logout</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-navigation-drawer>
            <v-app-bar color="transparent" app dense clipped-left>
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
                    <v-btn
                        text to="/"
                        active-class="pink lighten-4">
                        <v-icon to="/">home</v-icon>
                            ホーム
                    </v-btn>
                    <v-btn
                        text to="/mypage"
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
            userItems: [
                { title: "Home", icon: "home", to: "/post"},
                { title: "Post", icon: "question_answer", to: "/posting" },
                { title: "Mypage", icon: "dashboard", to: "/mypage"},
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
        },
        async logout () {
            await this.$store.dispatch('auth/logout')

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
        ...mapState({
            apiStatus: state => state.auth.apiStatus,
            loginErrors: state => state.auth.loginErrorMessages
        })
    }
}
</script>