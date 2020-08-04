<template>
  <v-row
    justify="center">
    <v-col
      cols="12"
      sm="8"
      md="6">
        <v-card>
          <v-card-title class="justify-center">Register</v-card-title>
          <form class="pa-8" @submit.prevent="register">
            <div v-if="registerErrors" class="errors">
              <ul v-if="registerErrors.name">
                <li v-for="msg in registerErrors.name" :key="msg">{{ msg }}</li>
              </ul>
              <ul v-if="registerErrors.login_id">
                <li v-for="msg in registerErrors.login_id" :key="msg">{{ msg }}</li>
              </ul>
              <ul v-if="registerErrors.password">
                <li v-for="msg in registerErrors.password" :key="msg">{{ msg }}</li>
              </ul>
            </div>
            <v-text-field
              v-model="registerForm.name"
              :rules="[rules.required]"
              counter
              clearable
              label="名前"
            ></v-text-field>
            <v-text-field
              v-model="registerForm.login_id"
              counter
              :rules="[rules.required, rules.min]"
              clearable
              label="ログインID"
            ></v-text-field>
            <v-text-field
              v-model="registerForm.password"
              counter
              :rules="[rules.required, rules.min]"
              :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
              :type="show1 ? 'text' : 'password'"
              @click:append="show1 = !show1"
              clearable
              label="パスワード"
            ></v-text-field><v-text-field
              v-model="registerForm.password_confirmation"
              counter
              :rules="[rules.required, rules.min]"
              :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
              :type="show1 ? 'text' : 'password'"
              @click:append="show1 = !show1"
              clearable
              label="パスワード（確認）"
            ></v-text-field>
            <div class="text-center">
              <v-btn type="submit" width="160" class="ma-2 mt-10" outlined color="pink lighten-1">送信</v-btn>
            </div>
          </form>
        </v-card>
    </v-col>
  </v-row>
</template>

<script>
import { mapState } from 'vuex'

export default {
  data () {
    return {
      registerForm: {
        name: '',
        login_id: '',
        password: '',
        password_confirmation: '',
      },
      show1: false,
      rules: {
          required: value => !!value || '必須項目です。',
          min: v => v.length >= 8 || '8文字以上入力してください。',
      }
    }
  },
  computed: mapState({
    apiStatus: state => state.auth.apiStatus,
    registerErrors: state => state.auth.registerErrorMessages
  }),
  methods: {
    async register () {
      await this.$store.dispatch('auth/register', this.registerForm)

      if (this.apiStatus) {
        this.$router.push('/')
      }
    },
    clearError () {
        this.$store.commit('auth/setRegisterErrorMessages', null)
    }
  },
  created () {
      this.clearError()
  }
}
</script>