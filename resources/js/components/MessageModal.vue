<template>
  <v-row justify="center">
    <v-dialog v-model="dialog" width="500">
      <v-card>
        <v-card-title class="justify-center"><slot>Message</slot></v-card-title>
        <v-form
          @submit.prevent="create"
          v-model="valid"
          ref="form">
          <div v-if="errors" class="errors red--text">
            <ul v-if="errors.message_text">
              <li v-for="msg in errors.message_text" :key="msg">{{ msg }}</li>
            </ul>
          </div>
          <v-textarea
            class="ma-5"
            v-model="text"
            filled
            auto-grow
            counter
            maxlength="300"
            placeholder="content"
            clearable
            :rules="[rules.required, rules.maxText]"
          >
          </v-textarea>
          <v-card-actions class="justify-center">
            <v-btn
              type="submit"
              width="160"
              class="mb-5"
              outlined
              color="pink lighten-1"
              @click="close"
              :disabled="!valid"
            >送る</v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
export default {
  props: {
    errors: {
      type: Object
    }
  },
  data () {
    return {
        text: this.text,
        dialog: false,
        rules: {
          required: value => !!value || '必須項目です。',
          maxText: v => (v && v.length <= 300) || '300文字以内で入力してください。',
        },
        valid: true,
    }
  },
  methods: {
    create () {
      if (!this.$refs.form.validate()) {
        return false
      }

      this.$emit('create', {
        text: this.text
      })
    },
    open () {
      this.dialog = true
    },
    close () {
      this.dialog = false
    }
  }
}
</script>