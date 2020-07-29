<template>
  <div class="post-form text-center">
    <div class="h2">メッセージを募集する</div>
    <form class="form mt-10" @submit.prevent="createPost">
      <v-col cols="12">
          <v-text-field
            v-model="post_title"
            counter
            maxlength="100"
            clearable
            label="タイトル"
          ></v-text-field>
      </v-col>
      <v-col cols="12">
          <v-text-field
            v-model="post_password"
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
            :type="show1 ? 'text' : 'password'"
            @click:append="show1 = !show1"
            clearable
            label="パスワード"
          ></v-text-field>
      </v-col>
      <v-col cols="6">
          <v-text-field
            v-model="max_number"
            type="number"
            clearable
            label="最大人数"
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
      </v-col>
        <v-file-input
            :rules="rules"
            accept="image/*"
            label="画像のアップロード"
            prepend-icon="photo"
            multiple
            v-model="files"
            @change="onFileChange"
            show-size
            counter
          >
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
      <v-btn type="submit" width="160" class="ma-2 mt-10" outlined color="pink lighten-1">送信</v-btn>
    </form>
  </div>
</template>

<script>
export default {
    data () {
        return {
            post_title: '',
            post_password: '',
            max_number: '',
            post_photo: [],
            show1: false,
            files: [],
            readers: [],
            index: '',
            rules: [
              value => !value.length || value.reduce((size, file) => size + file.size, 0) < 10240000 || 'サイズを10MB以内に抑えてください。',
            ]
        }
    },
    methods: {
        async createPost () {
          const formData = new FormData()

          formData.append('post_title', this.post_title)
          formData.append('post_password', this.post_password)
          formData.append('max_number', this.max_number)

          if (this.files.length > 0) {

            for (let index = 0; index < this.files.length; index++) {
              formData.append('post_photo[]', this.post_photo[index])
            }
          }

          const response = await axios.post('/api/posting', formData)
          this.post_title = ''
          this.post_password = ''
          this.max_number = ''
          this.post_photo = ''

          this.$router.push(`/post/${response.data.id}`)
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
        }
    }
}
</script>

