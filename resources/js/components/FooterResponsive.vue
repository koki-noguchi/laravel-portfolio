<template>
    <div>
        <v-bottom-navigation absolute v-if="!isLogin">
            <v-btn
                v-for="item in items"
                :key="item.title"
                :to="item.to"
                class="text-decoration-none">
                <span>{{ item.title }}</span>
                <v-icon>{{ item.icon }}</v-icon>
            </v-btn>
            <v-btn
                class="text-decoration-none"
                @click.prevent="guestLogin">
                <span>Guest Login</span>
                <v-icon>verified_user</v-icon>
            </v-btn>
        </v-bottom-navigation>
        <v-bottom-navigation absolute v-else>
            <v-btn
                v-for="item in userItems"
                :key="item.title"
                :to="item.to"
                class="text-decoration-none">
                <span>{{ item.title }}</span>
                <v-icon>{{ item.icon }}</v-icon>
            </v-btn>
            <v-btn
                class="text-decoration-none"
                @click="logout">
                <span>Logout</span>
                <v-icon>logout</v-icon>
            </v-btn>
        </v-bottom-navigation>
    </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
    data () {
        return {
            loginForm: {
                login_id: 'guest001',
                password: 'i29tg58f',
            },
            items: [
                { title: "Home", icon: "home", to: "/"},
                { title: "Register", icon: "how_to_reg", to: "/register" },
                { title: "Login", icon: "account_box", to: "/login" },
            ],
            userItems: [
                { title: "Home", icon: "home", to: "/post"},
                { title: "Post", icon: "question_answer", to: "/posting" },
                { title: "Mypage", icon: "dashboard", to: "/mypage"},
            ]
        }
    },
    methods: {
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
        isLogin () {
            return this.$store.getters['auth/check']
        },
        ...mapState({
            apiStatus: state => state.auth.apiStatus,
            loginErrors: state => state.auth.loginErrorMessages
        })
    }
}
</script>