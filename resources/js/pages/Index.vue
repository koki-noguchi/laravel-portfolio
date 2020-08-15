<template>
    <v-row>
        <v-col cols="12" class="text-center white--text red lighten-2 pa-6">
            <div class="h1 font-weight-black">
                MessageShare
            </div>
            <div class="subtitle-1 mt-5">
                MessageShareはテーマに沿ったメッセージを募集して、
            </div>
            <div class="subtitle-1 mt-2">
                みんなで共有するサービスです。
            </div>
            <v-row align="center" v-if="!isLogin">
                <v-col cols="12">
                    <div class="text-center">
                        <v-btn
                            class="font-weight-bold mt-8 white--text"
                            outlined rounded
                            width="210"
                            to="/register">新規登録はこちら</v-btn>
                    </div>
                    <div class="text-center">
                        <v-btn
                            class="font-weight-bold mt-8 white--text"
                            outlined rounded
                            width="210"
                            @click.prevent="guestLogin">ゲストとしてログインする</v-btn>
                    </div>
                </v-col>
            </v-row>
        </v-col>
        <v-col cols="12">
            <div class="h2 text-center mt-12">
                メッセージ募集の最新一覧
            </div>
            <Post
                v-for="post in posts"
                :key="post.id"
                :item="post"
            />
        </v-col>
    </v-row>
</template>

<script>
import { OK } from '../util'
import { mapState } from 'vuex'
import Post from '../components/Post.vue'

export default {
    components: {
        Post
    },
    data () {
        return  {
            posts: [],
            loginForm: {
                login_id: 'guest001',
                password: 'i29tg58f',
            },
        }
    },
    methods: {
        async fetchPost () {
            const response = await axios.get('/api/post')

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.posts = response.data.data
        },
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
    watch: {
        $route: {
            async handler () {
                await this.fetchPost()
            },
            immediate: true
        }
    },
    computed: {
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
