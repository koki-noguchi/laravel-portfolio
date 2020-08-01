<template>
  <v-row justify="center">
    <v-dialog v-model="dialog" width="500">
      <v-card>
        <v-card-title class="justify-center">Message</v-card-title>
        <v-textarea
          class="ma-5"
          v-model="text"
          filled
          auto-grow
          counter
          maxlength="300"
          placeholder="content"
          clearable
        >
        </v-textarea>
        <v-card-actions class="justify-center">
          <v-btn
            width="160"
            class="mb-5"
            outlined
            color="pink lighten-1"
            @click.prevent="create"
            @click="close"
          >送る</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
export default {
  data () {
    return {
        text: this.text,
        dialog: false,
    }
  },
  methods: {
    create () {
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