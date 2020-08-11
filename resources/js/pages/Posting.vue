<template>
    <v-row
      justify="center">
      <v-col
        cols="12"
        sm="8"
        md="6">
        <div class="post-form text-center">
          <div class="h2">メッセージを募集する</div>
          <v-form
            class="form mt-10"
            @submit.prevent="createPost"
            v-model="valid"
            ref="form">
            <div v-if="errors" class="errors red--text">
              <ul v-if="errors.post_title">
                <li v-for="msg in errors.post_title" :key="msg">{{ msg }}</li>
              </ul>
              <ul v-if="errors.about">
                <li v-for="msg in errors.about" :key="msg">{{ msg }}</li>
              </ul>
              <ul v-if="errors.max_number">
                <li v-for="msg in errors.max_number" :key="msg">{{ msg }}</li>
              </ul>
              <ul v-if="errors.post_photo">
                <li v-for="msg in errors.post_photo" :key="msg">{{ msg }}</li>
              </ul>
            </div>
            <v-text-field
              v-model="posts.post_title"
              counter
              maxlength="32"
              clearable
              label="タイトル"
              :rules="[rules.required, rules.maxTitle]"
            ></v-text-field>
            <v-textarea
              class="mt-5"
              v-model="posts.about"
              filled=""
              auto-grow
              counter
              maxlength="2000"
              clearable
              label="募集の概要： ルールや説明などを入力してください。"
              :rules="[rules.required, rules.maxAbout]"
            ></v-textarea>
            <v-flex lg6 md6>
              <v-text-field
                v-model="posts.max_number"
                type="number"
                clearable
                label="最大人数"
                oninput="if(this.value < 1) this.value = 1"
                :rules="[rules.required]"
              >
                <template v-slot:prepend>
                  <v-tooltip
                    bottom
                  >
                    <template v-slot:activator="{ on }">
                      <v-icon v-on="on">mdi-help-circle-outline</v-icon>
                    </template>
                    メッセージ数が最大人数分に達すると募集が締めきられます。
                  </v-tooltip>
                </template>
              </v-text-field>
            </v-flex>
            <v-file-input
                :rules="[rules.size]"
                accept="image/*"
                label="画像のアップロード"
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
            <v-col sm="4" v-for="(file,f) in files" :key="f">
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
              outlined
              color="pink lighten-1"
              :disabled="!valid">送信</v-btn>
          </v-form>
        </div>
      </v-col>
    </v-row>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../util'

export default {
    data () {
        return {
          posts: {
            post_title: '',
            about: '',
            max_number: '',
            post_photo: [],
          },
            show1: false,
            files: [],
            readers: [],
            index: '',
            rules: {
              size: value => !value.length || value.reduce((size, file) => size + file.size, 0) < 10240000 || 'サイズを10MB以内に抑えてください。',
              required: value => !!value || '必須項目です。',
              maxTitle: v => (v && v.length <= 32) || '32文字以内で入力してください。',
              maxAbout: v => (v && v.length <= 2000) || '2000文字以内で入力してください。',
            },
            valid: true,
            errors: null,
        }
    },
    methods: {
        async createPost () {
          const formData = new FormData()

          formData.append('post_title', this.posts.post_title)
          formData.append('about', this.posts.about)
          formData.append('max_number', this.posts.max_number)

          if (this.files.length > 0) {

            for (let index = 0; index < this.files.length; index++) {
              formData.append('post_photo[]', this.posts.post_photo[index])
            }
          }

          if (!this.$refs.form.validate()) {
            return false
          }

          const response = await axios.post('/api/posting', formData)

          if (response.status === UNPROCESSABLE_ENTITY) {
            this.errors = response.data.errors
            this.$store.commit('message/setErrorContent', {
              errorContent: 'エラーが発生しました。',
            })
            return false
          }

          this.reset()

          if (response.status !== CREATED) {
            this.$store.commit('error/setCode', response.status)
            this.$store.commit('message/setErrorContent', {
              errorContent: 'エラーが発生しました。',
            })
            return false
          }

          this.$store.commit('message/setSuccessContent', {
            successContent: '募集を開始しました。',
          })
          this.$router.push(`/post/${response.data.id}`)
        },
        reset() {
          this.posts.post_title = ''
          this.posts.about = ''
          this.posts.max_number = ''
          this.posts.post_photo = ''
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
                this.posts.post_photo[f] = this.files[f]
            })
        }
    }
}
</script>

