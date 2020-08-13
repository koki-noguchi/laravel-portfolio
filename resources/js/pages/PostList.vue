<template>
  <div class="post-list">
    <Search
      @search="search($event)">
    </Search>
    <Post
      v-for="post in posts"
      :key="post.id"
      :item="post"
      @bookmark="onBookmarkClick"
    />
    <infinite-loading
      @infinite="infiniteHandler"
      :identifier="infiniteId"
      direction="bottom"
      spinner="spiral">
      <div slot="no-more"></div>
      <div slot="no-results">データがありません</div>
    </infinite-loading>
  </div>
</template>

<script>
import { OK } from '../util'
import Post from '../components/Post.vue'
import Search from '../components/Search.vue'
import InfiniteLoading from 'vue-infinite-loading';
import ReplyVue from '../components/Reply.vue'

export default {
  components: {
    Post,
    Search,
    InfiniteLoading
  },
  props: {
    type: Number,
    required: false,
    default: 1
  },
  data () {
    return {
      posts: [],
      urlParams: '',
      page: 1,
      infiniteId: 0,
      key: '',
    }
  },
  methods: {
    async search (params) {
      this.urlParams = params
      let page = "?page=1"
      let url = "/api/post/" + page + this.urlParams
      this.$router.push("/post/" + page + this.urlParams)
      this.resetHandler()
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
    infiniteHandler($state) {
        axios.get(`/api/post/?page=${this.page}` + this.key)
        .then(response => {
          let posts = response.data.data
          setTimeout(() => {
            if (posts.length) {
              this.posts =this.posts.concat(posts)
              $state.loaded()
            } else {
              $state.complete()
            }
            ++this.page
          }, 1500)
        }).catch((err) => {
          $state.complete()
        })
    },
    resetHandler() {
      const keyword = window.location.search
      const key = '&' + keyword.split(`&`)[1]
      const page = keyword.substring(6).split('&')[0]

      this.key = key

      if (page) {
        this.page = page
      } else {
        this.page = 1
      }

      this.posts = []
      this.urlParams = ''
      this.infiniteId++
    }
  },
  watch: {
    $route: {
      async handler () {
        await this.resetHandler()
      },
      immediate: true
    }
  }
}
</script>