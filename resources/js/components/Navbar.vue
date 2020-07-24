<template>
    <nav class="navbar">
        <RouterLink class="navbar-brand" to="/">
            MessageShare
        </RouterLink>
        <div class="navbar-menu">
            <span v-if="isLogin" class="navbar-item">
                <RouterLink class="button button--link" to="/">
                    ホーム
                </RouterLink>
            </span>
            <span v-if="isLogin" class="navbar-item">
                {{ username }}
                <img :src="image">
            </span>
            <span v-if="isLogin !== true" class="navbar-item">
                <button class="button button--link" @click.prevent="guestLogin">
                    ゲストユーザーとしてログイン
                </button>
            </span>
            <span v-if="isLogin !== true" class="navbar-item">
                <RouterLink class="button button--link" to="/register">
                    新規登録
                </RouterLink>
            </span>
            <span v-if="isLogin !== true"  class="navbar-item">
                <RouterLink class="button button--link" to="/login">
                    ログイン
                </RouterLink>
            </span>
            <div class="post-search">
                <form class="form" @keydown.enter.prevent="search">
                    <div class="form__item">
                        <input
                        class="input--keyword"
                        type="text"
                        v-model="keyword"
                        placeholder="id or title">
                    </div>
                    <button class="btn button--search"><span></span></button>
                </form>
            </div>
        </div>
    </nav>
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
            }
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
        isLogin () {
            return this.$store.getters['auth/check']
        },
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