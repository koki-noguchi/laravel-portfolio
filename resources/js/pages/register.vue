<template>
  <form class="form" @submit.prevent="register">
    <label for="username">名前</label>
    <input type="text" class="form__item" id="username" v-model="name">
    <label for="userid">ID</label>
    <input type="text" class="form__item" id="userid" v-model="user_id">
    <label for="password">パスワード</label>
    <input type="password" class="form__item" id="password" v-model="password">
    <label for="password-confirmation">パスワード (確認)</label>
    <input type="password" class="form__item" id="password-confirmation" v-model="password_confirmation">
    <div class="form__button">
      <button type="submit" class="button button--inverse">register</button>
    </div>
  </form>
</template>

<script>
export default {
  data () {
    return {
      name: '',
      user_id: '',
      password,
      password_confirmation: '',
    }
  },
  methods: {
    register () {
      
    }
  }
}
</script>