<template>
    <nav class="navbar">
        <RouterLink class="navbar-brand" to="/">
            Message For You
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
        </div>
    </nav>
</template>

<script>
export default {
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