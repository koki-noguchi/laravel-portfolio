<template>
  <form class="form" @submit.prevent="register">
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
    <label for="username">名前</label>
    <input type="text" class="form__item" id="username" v-model="registerForm.name">
    <label for="login_id">ID</label>
    <input type="text" class="form__item" id="login_id" v-model="registerForm.login_id">
    <label for="password">パスワード</label>
    <input type="password" class="form__item" id="password" v-model="registerForm.password">
    <label for="password-confirmation">パスワード (確認)</label>
    <input type="password" class="form__item" id="password-confirmation" v-model="registerForm.password_confirmation">
    <div class="form__button">
      <button type="submit" class="button button--inverse">register</button>
    </div>
  </form>
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
        password_confirmation: ''
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