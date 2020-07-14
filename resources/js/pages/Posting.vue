<template>
  <div class="post-form">
    <h2 class="title">メッセージを募集する</h2>
    <form class="form" @submit.prevent="createPost">
      <input class="form__item" type="text" v-model="post_title" placeholder="タイトル">
      <input class="form__item" type="password" v-model="post_password" placeholder="パスワード">
      <input class="form__item" type="number" v-model="min_number" placeholder="最低人数">
      <input class="form__item" type="number" v-model="max_number" placeholder="最大人数">
      <div class="form__button">
        <button type="submit" class="button button--inverse">送信</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
    data () {
        return {
            post_title: '',
            post_password: '',
            min_number: '',
            max_number: '',
        }
    },
    methods: {
        async createPost () {
            const response = await axios.post('/api/posting', {
                post_title: this.post_title,
                post_password: this.post_password,
                min_number: this.min_number,
                max_number: this.max_number,
                })
            this.post_title = ''
            this.post_password = ''
            this.min_number = ''
            this.max_number = ''
        }
    }
}
</script>

