<template>
  <div class="post">
      <p class="post-user">{{ item.user.name }}</p>
      <div class="post-title">
          <p :to="'/post/{{item.id}}'">{{ item.post_title }}</p>
      </div>
      <div class="post-about">{{ item.about }}</div>
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