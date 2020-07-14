<template>
    <form class="form" @submit.prevent="login">
        <div v-if="loginErrors" class="errors">
            <ul v-if="loginErrors.login_id">
            <li v-for="msg in loginErrors.login_id" :key="msg">{{ msg }}</li>
            </ul>
            <ul v-if="loginErrors.password">
            <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
            </ul>
        </div>
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
import { mapState } from 'vuex'

export default {
    data () {
        return {
            loginForm: {
                login_id: '',
                password: '',
            }
        }
    },
    computed: mapState({
        apiStatus: state => state.auth.apiStatus,
        loginErrors: state => state.auth.loginErrorMessages
    }),
    methods: {
        async login () {
            await this.$store.dispatch('auth/login', this.loginForm)

            if (this.apiStatus) {
                this.$router.push('/')
            }
        },
        clearError () {
            this.$store.commit('auth/setLoginErrorMessages', null)
        }
    },
    created () {
        this.clearError()
    }
}
</script>