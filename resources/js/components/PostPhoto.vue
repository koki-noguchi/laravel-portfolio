<template>
<div>
    <v-carousel height="400" width="600">
        <v-carousel-item
            v-for="photo in photos"
            :key="photo.photos_url"
            :src="photo.photos_url"
        ></v-carousel-item>
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