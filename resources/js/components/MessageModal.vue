<template>
    <div>
        <textarea
          v-model="message_text"
        ></textarea>
        <div class="form__button">
          <button
            class="button button--inverse"
            @click.prevent="create"
          >送る</button>
        </div>
    </div>
</template>

<script>
export default {
  data () {
    return {
        message_text: this.message_text,
    }
  },
  methods: {
    create () {
      this.$emit('create', {
        message_text: this.message_text
      })
    }
  }
}
</script>