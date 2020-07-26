<template>
   <v-col
      class="text-center pink lighten-5"
      cols="12"
    >
      {{ new Date().getFullYear() }} — <strong>MessageShare</strong>
    </v-col>
</template>

<script>
export default {
    methods: {
        async logout () {
            await this.$store.dispatch('auth/logout')

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