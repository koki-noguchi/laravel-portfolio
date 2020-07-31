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
            <PostEditForm :id="this.id"></PostEditForm>
          </v-tab-item>
          <v-tab href="#photo">
            Photo
          </v-tab>
          <v-tab-item id="photo">
            <PostEditPhoto :id="this.id"></PostEditPhoto>
          </v-tab-item>
      </v-tabs>
    </div>
</template>

<script>
import { OK } from '../util'
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
