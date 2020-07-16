<template>
    <div>
        <p>タイトル</p>
        <input
          type="text"
          v-model="post_title">
        <p>募集条件</p>
        <textarea
          v-model="about"
        ></textarea>
        <div class="form__button">
          <button
            class="button button--inverse"
            @click.prevent="update"
          >送信</button>
        </div>
    </div>
</template>

<script>
export default {
  props:
    [
      'post'
    ]
  ,
  data () {
    return {
        post_title: this.post.post_title,
        about: this.post.about,
    }
  },
  methods: {
    update () {
      this.$emit('update', {
        post_title: this.post_title,
        about: this.about
      })
    }
  }
}
</script>
