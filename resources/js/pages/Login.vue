<template>
    <form class="form" @submit.prevent="login">
        <label for="login-id">ID</label>
        <input type="text" class="form__item" id="login-id" v-model="loginForm.login_id">
        <label for="login-password">Password</label>
        <input type="password" class="form__item" id="login-password" v-model="loginForm.password">
        <div class="form__button">
        <button type="submit" class="button button--inverse">login</button>
        </div>
    </form>
</template>

<script>
export default {
    data () {
        return {
            loginForm: {
                login_id: '',
                password: '',
            }
        }
    },
    methods: {
        async login () {
            await this.$store.dispatch('auth/login', this.loginForm)

            this.$router.push('/')
        }
    }
}
</script>