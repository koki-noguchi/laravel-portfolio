<template>
    <Post></Post>
</template>

<script>
import { OK } from '../util'
import Post from '../components/Post.vue'

export default {
    components: {
        Post
    },
    props: {
        id: {
            type: String,
            required: true
        }
    },
    data () {
        return {
            user: ''
        }
    },
    methods: {
        async fetchProfile () {
            const response = await axios.get(`/api/users/${this.id}`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.user = response.data
        }
    }
}
</script>