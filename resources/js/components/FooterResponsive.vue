<template>
        <v-bottom-navigation height="50" fixed v-if="!isLogin">
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
                <span>ゲストログイン</span>
                <v-icon>verified_user</v-icon>
            </v-btn>
        </v-bottom-navigation>
        <v-bottom-navigation height="50" fixed v-else>
            <v-btn
                v-for="item in userItems"
                :key="item.title"
                :to="item.to"
                class="text-decoration-none">
                <span>{{ item.title }}</span>
                <v-icon>{{ item.icon }}</v-icon>
            </v-btn>
            <v-btn
                :to="`/users/${user_id}/timeline`"
                class="text-decoration-none">
                <span>マイページ</span>
                <v-icon>dashboard</v-icon>
            </v-btn>
            <v-btn
                class="text-decoration-none"
                @click="logout">
                <span>ログアウト</span>
                <v-icon>logout</v-icon>
            </v-btn>
        </v-bottom-navigation>
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
                { title: "ホーム", icon: "home", to: "/"},
                { title: "登録", icon: "how_to_reg", to: "/register" },
                { title: "ログイン", icon: "account_box", to: "/login" },
            ],
            userItems: [
                { title: "一覧", icon: "view_list", to: "/post"},
                { title: "募集する", icon: "question_answer", to: "/posting" },
            ]
        }
    },
    methods: {
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
        isLogin () {
            return this.$store.getters['auth/check']
        },
        user_id () {
            return this.$store.getters['auth/id']
        },
        ...mapState({
            apiStatus: state => state.auth.apiStatus,
            loginErrors: state => state.auth.loginErrorMessages
        })
    }
}
</script>