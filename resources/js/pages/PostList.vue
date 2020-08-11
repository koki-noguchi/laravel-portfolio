<template>
  <div class="post-list">
    <Post
      v-for="post in posts"
      :key="post.id"
      :item="post"
      @bookmark="onBookmarkClick"
    />
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
    onBookmarkClick ({id, bookmarked_by_user}) {
      if (bookmarked_by_user) {
          this.deleteBookmark(id)
      } else {
          this.bookmark(id)
      }
    },
    async bookmark (id) {
      const response = await axios.put(`/api/post/${id}/bookmark`)

      if (response.status !== OK) {
          this.$store.commit('error/setCode', response.status)
          this.$store.commit('message/setErrorContent', {
            errorContent: 'エラーが発生しました。',
          })
          return false
      }

      this.posts = this.posts.map(post => {
          if (post.id === response.data.post_id) {
              post.bookmarked_by_user = true
          }
          return post
      })

      this.$store.commit('message/setSuccessContent', {
            successContent: 'ブックマークしました。',
      })
    },
    async deleteBookmark (id) {
      const response = await axios.delete(`/api/post/${id}/bookmark`)

      if (response.status !== OK) {
          this.$store.commit('error/setCode', response.status)
          this.$store.commit('message/setErrorContent', {
            errorContent: 'エラーが発生しました。',
          })
          return false
      }

      this.posts = this.posts.map(post => {
          if (post.id === response.data.post_id) {
              post.bookmarked_by_user = false
          }
          return post
      })

      this.$store.commit('message/setSuccessContent', {
            successContent: 'ブックマークを外しました。',
      })
    },
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