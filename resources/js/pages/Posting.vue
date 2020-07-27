<template>
  <div class="post-form text-center">
    <div class="h2">メッセージを募集する</div>
    <form class="form mt-10" @submit.prevent="createPost">
      <v-col cols="12">
          <v-text-field
            v-model="post_title"
            :rules="rules"
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
        v-model="files"
        placeholder="Upload your images"
        label="画像のアップロード"
        multiple
        prepend-icon="mdi-paperclip"
        show-size
      >
        <template v-slot:selection="{ text }">
          <v-chip
            small
            label
            color="primary"
          >
            {{ text }}
          </v-chip>
        </template>
      </v-file-input>
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
            min_number: '',
            max_number: '',
            show1: false,
            files: [],
        }
    },
    methods: {
        async createPost () {
            const response = await axios.post('/api/posting', {
                post_title: this.post_title,
                post_password: this.post_password,
                min_number: this.min_number,
                max_number: this.max_number,
                })
            this.post_title = ''
            this.post_password = ''
            this.min_number = ''
            this.max_number = ''

            this.$router.push(`/post/${response.data.id}`)
        }
    }
}
</script>

