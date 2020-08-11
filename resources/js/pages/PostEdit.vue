<template>
    <div v-if="post">
      <h1 class="text-center">編集ページ</h1>
      <v-row justify="center">
        <v-col
          cols="12"
          sm="8"
          md="6"
        >
          <v-btn @click.prevent="$router.go(-1)">戻る</v-btn>
        </v-col>
      </v-row>
      <v-tabs
        centered
      >
          <v-tab href="#post">
            Post
          </v-tab>
          <v-tab-item id="post">
            <PostEditForm
              :item_title="post.post_title"
              :item_about="post.about"
              :id="id"
              :errors="errors"
              @updatePost="updatePost"
            ></PostEditForm>
          </v-tab-item>
          <v-tab href="#photo">
            Photo
          </v-tab>
          <v-tab-item id="photo">
            <PostEditPhoto
              :photos_list="post.photos"
              :id="this.id"
              @photoDelete="photoDelete"
            ></PostEditPhoto>
          </v-tab-item>
      </v-tabs>
    </div>
</template>

<script>
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
import PostEditForm from '../components/PostEditForm.vue'
import PostEditPhoto from '../components/PostEditPhoto.vue'

export default {
  components: {
    PostEditForm,
    PostEditPhoto
  },
  props: {
    id: {
      type: String,
      required: true
    }
  },
  data () {
    return {
      post: null,
      errors: null,
    }
  },
  methods: {
    async fetchPost () {
      const response = await axios.get(`/api/post/${this.id}`)

      if (!response.data.my_post) {
        this.$router.push(`/post/${this.id}`)
      }

      if (response.status !== OK) {
          this.$store.commit('error/setCode', response.status)
          return false
      }

      this.post = response.data
    },
    async updatePost ({ post_title, about }) {
      const response = await axios.put(`/api/post/${this.id}`,
        {
            post_title: post_title,
            about: about
        })

      if (response.status === UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors
        this.$store.commit('message/setErrorContent', {
          errorContent: 'エラーが発生しました。',
        })
        return false
      }

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        this.$store.commit('message/setErrorContent', {
          errorContent: 'エラーが発生しました。',
        })
        return false
      }

      this.$store.commit('message/setSuccessContent', {
        successContent: '募集内容を変更しました。',
      })

      this.fetchPost()
    },
    async photoDelete ({ photo_id }) {
      const response = await axios.delete(`/api/photo/${photo_id}`)

      if (response.status !== OK) {
          this.$store.commit('error/setCode', response.status)
          this.$store.commit('message/setErrorContent', {
            errorContent: 'エラーが発生しました。',
          })
          return false
      }
      this.$store.commit('message/setSuccessContent', {
        successContent: '画像が削除されました。',
      })

      this.fetchPost()
    }
  },
  watch: {
    $route: {
        async handler () {
            await this.fetchPost()
        },
        immediate: true
    },
  }
}
</script>
