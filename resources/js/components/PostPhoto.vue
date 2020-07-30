<template>
<div>
    <v-carousel
        hide-delimiter-background
        show-arrows-on-hover
        height="300"
        class="grey darken-4"
        v-if="photos.length > 0">
    <v-carousel-item
        v-for="photo in photos"
        :key="photo.photos_url"
    >
        <v-img
        :src="photo.photos_url"
        contain
        height="300"></v-img>
    </v-carousel-item>
  </v-carousel>
</div>
</template>

<script>
export default {
    props: {
        photos: {
            type: Array,
        }
    },
    methods: {
        next () {
            this.onboarding = this.onboarding + 1 === this.length
                ? 0
                : this.onboarding + 1
        },
        prev () {
            this.onboarding = this.onboarding - 1 < 0
            ? this.length - 1
            : this.onboarding -1
        }
    }
}
</script>