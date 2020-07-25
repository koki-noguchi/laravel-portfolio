<template>
  <div class="post">
      <button
        class="post__action post__action--bookmark"
        :class="{ 'post__action--bookmarked': item.bookmarked_by_user }"
        title="Bookmark post"
        @click.prevent="bookmark"
        >
      <i class="icon ion-md-heart">bookmark</i></button>
      <p class="post-user">{{ item.user.name }}</p>
      <div class="post-title">
          <RouterLink :to="`/post/${item.id}`">{{ item.post_title }}</RouterLink>
      </div>
      <div class="post-about">{{ item.about }}</div>
      <p v-if="item.password_judge === true" class="post-judge">※パスワードあり</p>
  </div>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  methods: {
    bookmark () {
      this.$emit('bookmark', {
        id: this.item.id,
        bookmarked: this.item.bookmarked_by_user
      })
    }
  }
}
</script>