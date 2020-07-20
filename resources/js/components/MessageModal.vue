<template>
    <div>
        <textarea
          v-model="text"
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
        text: this.text,
    }
  },
  methods: {
    create () {
      this.$emit('create', {
        text: this.text
      })
    }
  }
}
</script>