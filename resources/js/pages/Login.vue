<template>
    <v-row
        justify="center">
        <v-col
            cols="12"
            sm="8"
            md="6">
            <v-card>
                <v-card-title class="justify-center">Login</v-card-title>
                <v-form
                    class="pa-8"
                    @submit.prevent="login"
                    ref="form"
                    v-model="valid"
                    lazy-validation>
                    <div v-if="loginErrors" class="errors red--text">
                        <ul v-if="loginErrors.login_id">
                        <li v-for="msg in loginErrors.login_id" :key="msg">{{ msg }}</li>
                        </ul>
                        <ul v-if="loginErrors.password">
                        <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
                        </ul>
                    </div>
                    <v-text-field
                        v-model="loginForm.login_id"
                        :rules="[rules.required]"
                        clearable
                        label="ログインID"
                    ></v-text-field>
                    <v-text-field
                        v-model="loginForm.password"
                        counter
                        :rules="[rules.required]"
                        :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="show1 ? 'text' : 'password'"
                        @click:append="show1 = !show1"
                        clearable
                        label="パスワード"
                    ></v-text-field>
                    <div class="text-center">
                        <v-btn
                            type="submit"
                            width="160"
                            class="ma-2 mt-10"
                            outlined color="pink lighten-1"
                            :disabled="!valid">送信</v-btn>
                    </div>
                </v-form>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import { mapState } from 'vuex'

export default {
    data () {
        return {
            loginForm: {
                login_id: '',
                password: '',
            },
            show1: false,
            rules: {
                required: value => !!value || '必須項目です。',
            },
            valid: true,
        }
    },
    computed: mapState({
        apiStatus: state => state.auth.apiStatus,
        loginErrors: state => state.auth.loginErrorMessages
    }),
    methods: {
        async login () {
            if (!this.$refs.form.validate()) {
                return false
            }

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