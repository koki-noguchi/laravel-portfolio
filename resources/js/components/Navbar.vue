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
export default {
    data () {
        return {
            keyword: '',
            posts: []
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
        }
    },
    computed : {
        isLogin () {
            return this.$store.getters['auth/check']
        },
        username () {
            return this.$store.getters['auth/username']
        }
    }
}
</script>