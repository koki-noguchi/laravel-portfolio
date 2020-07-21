<template>
  <div class="post-list">
    <div class="grid">
      <Post
        class="grid__item"
        v-for="post in posts"
        :key="post.id"
        :item="post"
        @bookmark="onBookmarkClick"
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
      const url = location.href.split('/post')
      let response = []
      if (url[1]) {
        response = await axios.get('/api/post' + url[1])
      } else {
        response = await axios.get('/api/post')
      }
      if (response.status !== OK) {
          this.$store.commit('error/setCode', response.status)
          return false
        }
      this.posts = response.data.data
    },
    onBookmarkClick ({ id, bookmarked }) {
      if (! this.$store.getters['auth/check']) {
        alert('ブックマーク機能を使うにはログインが必要です。')
        return false
      }

      if (bookmarked) {
        this.deleteBookmark(id)
      } else {
        this.bookmark(id)
      }
    },
    async bookmark (id) {
      const response = await axios.put(`/api/post/${id}/bookmark`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.posts = this.posts.map(post => {
        if (post.id === response.data.post_id) {
          post.bookmarked_by_user = true
        }
        return post
      })
    },
    async deleteBookmark (id) {
      const response = await axios.delete(`/api/post/${id}/bookmark`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.posts = this.posts.map(post => {
        if (post.id === response.data.post_id) {
          post.bookmarked_by_user = false
        }
        return post
      })
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