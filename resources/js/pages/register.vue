<template>
  <form class="form" @submit.prevent="register">
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
  methods: {
    async register () {
      await this.$store.dispatch('auth/register', this.registerForm)

      this.$router.push('/')
    }
  }
}
</script>