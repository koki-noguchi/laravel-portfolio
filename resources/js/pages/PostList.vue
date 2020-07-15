<template>
  <div class="post-list">
    <div class="grid">
      <Post
        class="grid__item"
        v-for="post in posts"
        :key="post.id"
        :item="post"
      />
    </div>
  </div>
</template>

<script>
import { OK } from '../util'
import Post from '../components/Post.vue'

export default {
  components: {
    Post
  },
  data () {
    return {
      posts: []
    }
  },
  methods: {
    async fetchPosts () {
      const response = await axios.get('/api/post')

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.posts = response.data.data
    }
  },
  watch: {
    $route: {
      async handler () {
        await this.fetchPosts()
      },
      immediate: true
    }
  }
}
</script>