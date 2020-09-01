<template>
    <v-row>
        <Description />
        <v-col cols="12">
            <div class="h2 text-center mt-12">
                最新のメッセージ募集
            </div>
            <Post
                v-for="post in posts"
                :key="post.id"
                :item="post"
            />
        </v-col>
    </v-row>
</template>

<script>
import { OK } from '../util'
import { mapState } from 'vuex'
import Description from '../components/ServiceDescription.vue'
import Post from '../components/Post.vue'

export default {
    components: {
        Description,
        Post,
    },
    data () {
        return  {
            posts: []
        }
    },
    methods: {
        async fetchPost () {
            const response = await axios.get('/api/post')

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.posts = response.data.data
        },
    },
    watch: {
        $route: {
            async handler () {
                await this.fetchPost()
            },
            immediate: true
        }
    },
    computed: {
        ...mapState({
            apiStatus: state => state.auth.apiStatus,
            loginErrors: state => state.auth.loginErrorMessages
        })
    }
}
</script>
