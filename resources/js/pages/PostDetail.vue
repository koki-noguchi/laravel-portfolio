<template>
    <div v-if="post" class="post-detail">
        <h1 class="post-detail__title">{{ post.post_title }}</h1>
        <div class="post-detail__about">
            {{ post.about }}
        </div>
        <div class="post-detail__name">
            {{ post.user.name}}
        </div>
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
            post: null
        }
    },
    methods: {
        async fetchPost () {
            const response = await axios.get(`/api/post/${this.id}`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.post = response.data
        }
    },
    watch: {
        $route: {
            async handler () {
                await this.fetchPost()
            },
            immediate: true
        }
    }
}
</script>