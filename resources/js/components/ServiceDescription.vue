<template>
    <v-col cols="12" class="text-center white--text red lighten-2 pa-6">
        <div class="h1 font-weight-black">
            MessageShare
        </div>
        <div class="subtitle-1 mt-5">
            MessageShareはテーマに沿ったメッセージを募集して、
        </div>
        <div class="subtitle-1 mt-2">
            出来上がったページをみんなで共有するサービスです。
        </div>
        <v-row align="center" v-if="!isLogin">
            <v-col cols="12">
                <div class="text-center">
                    <v-btn
                        class="font-weight-black mt-2 white--text"
                        outlined rounded
                        width="210"
                        to="/register">新規登録はこちら</v-btn>
                </div>
                <div class="text-center">
                    <v-btn
                        class="font-weight-black mt-4 white--text"
                        outlined rounded
                        width="210"
                        @click.prevent="guestLogin">ゲストとしてログインする</v-btn>
                </div>
            </v-col>
        </v-row>
    </v-col>
</template>

<script>
export default {
    data () {
        return {
            loginForm: {
                login_id: 'guest001',
                password: 'i29tg58f',
            },
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
        }
    },
    computed: {
        isLogin () {
            return this.$store.getters['auth/check']
        },
    }
}
</script>