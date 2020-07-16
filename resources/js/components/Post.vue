<template>
  <div class="post">
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
  }
}
</script>