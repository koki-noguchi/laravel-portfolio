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
            <v-row justify="center">
              <v-col
                cols="12"
                sm="8"
                md="6"
              >
                <form
                  class="mt-10 text-center"
                  @submit.prevent="update"
                >
                  <v-text-field
                    class="mt-5"
                    v-model="post_title"
                    filled
                    counter
                    maxlength="100"
                    clearable
                    label="タイトル"
                  >
                  </v-text-field>
                  <v-textarea
                    class="mt-5"
                    v-model="about"
                    filled=""
                    auto-grow
                    counter
                    maxlength="2000"
                    clearable
                    label="概要"
                  >
                  </v-textarea>
                  <v-btn
                    type="submit"
                    class="mt-5"
                    width="160"
                    outlined color="pink lighten-1"
                  >送信</v-btn>
                </form>
              </v-col>
            </v-row>
          </v-tab-item>
          <v-tab href="#photo">
            Photo
          </v-tab>
          <v-tab-item id="photo">
            <v-row justify="center">
              <v-col
                  cols="12"
                  sm="8"
                  md="6"
                >
                <form
                  class="mt-10 text-center"
                  @submit.prevent="create"
                >
                  <v-file-input
                      class="mt-5"
                      :rules="rules"
                      accept="image/*"
                      label="画像の追加"
                      prepend-icon="photo"
                      multiple
                      v-model="files"
                      @change="onFileChange"
                      show-size
                      counter
                  >
                    <template v-slot:selection="{ text }">
                      <v-chip
                        small
                        label
                        color="primary"
                        close
                        @click:close="remove(index)"
                      >
                        {{ text }}
                      </v-chip>
                    </template>
                  </v-file-input>
                  <v-row>
                    <v-col sm="6" v-for="(file,f) in files" :key="f">
                        {{file.name}}
                        <img
                          :ref="'file'"
                          src=""
                          class="img-fluid"
                          :title="'file' + f"
                        />
                    </v-col>
                  </v-row>
                  <v-btn
                    type="submit"
                    width="160"
                    class="ma-2 mt-10"
                    outlined color="pink lighten-1"
                  >送信</v-btn>
                </form>
              </v-col>
            </v-row>
            <v-row v-if="photos.length > 0">
              <v-col sm="4"
                v-for="photo in photos"
                :key="photo.photos_url"
              >
                <v-card>
                  <v-img
                    :src="photo.photos_url"
                  >
                  </v-img>
                  <v-card-actions>
                    <v-btn
                      color="orange"
                      text
                      @click.prevent="photoDelete(photo.id)"
                    >
                    Delete
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-col>
            </v-row>
          </v-tab-item>
      </v-tabs>
    </div>
</template>

<script>
import { OK } from '../util'

export default {
  props: {
    id: {
      type: String,
      required: true
    }
  },
  data () {
    return {
      post: null,
      post_title: '',
      about: '',
      photos: [],
      post_photo: [],
      files: [],
        readers: [],
        index: '',
        rules: [
          value => !value.length || value.reduce((size, file) => size + file.size, 0) < 10240000 || 'サイズを10MB以内に抑えてください。',
      ]
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
      this.post_title = this.post.post_title
      this.about = this.post.about
      this.photos = this.post.photos
    },
    async update () {
      const response = await axios.put(`/api/post/${this.id}`,
      {
          post_title: this.post_title,
          about: this.about
      })
      await this.fetchPost()
    },
    async create () {
      const formData = new FormData()

      if (this.files.length > 0) {
        for (let index = 0; index < this.files.length; index++) {
          formData.append('post_photo[]', this.post_photo[index])
        }
      }

      const response = await axios.post(`/api/post/${this.id}/photo`, formData)
      this.post_photo = ''

      this.$router.push(`/post/${this.id}`)
    },
    remove (index) {
      this.files.splice(index, 1)
    },
    onFileChange () {
      this.files.forEach((file, f) => {
        this.readers[f] = new FileReader();
        this.readers[f].onloadend = (e) => {
            let fileData = this.readers[f].result
            let imgRef = this.$refs.file[f]
            imgRef.src = fileData
        }

        this.readers[f].readAsDataURL(this.files[f]);
        this.post_photo[f] = this.files[f]
      })
    },
    async photoDelete (id) {
      const response = await axios.delete(`/api/photo/${id}`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.fetchPost()
    }
  },
  computed: {
      isLogin () {
          return this.$store.getters['auth/check']
      },
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
