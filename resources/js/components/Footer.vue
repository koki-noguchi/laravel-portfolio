<template>
    <v-col
        class="text-center pink lighten-5 d-flex flex-column"
        cols="12"
    >
        <div class="d-flex flex-row justify-center">
            {{ new Date().getFullYear() }} — <strong>MessageShare</strong>
        </div>
        <RouterLink to="/about">MessageShareとは？</RouterLink>
    </v-col>
</template>

<script>
export default {
    methods: {
        async logout () {
            await this.$store.dispatch('auth/logout')

            this.$store.commit('message/setSuccessContent', {
                successContent: 'ログアウトしました。',
            })
            this.$router.push('/login')
        }
    },
    computed: {
        isLogin () {
            return this.$store.getters['auth/check']
        },
        username () {
            return this.$store.getters['auth/username']
        }
    }
}
</script>