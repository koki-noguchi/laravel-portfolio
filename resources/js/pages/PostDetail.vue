<template>
    <div v-if="post" class="post-detail">
        <div class="text-right">
            <v-btn
                v-if="post.my_post"
                class="ma-2 white--text text-decoration-none"
                color="blue lighten-2"
                :to="`/post/${id}/edit`"
            >edit
                <v-icon color="white">edit</v-icon>
            </v-btn>
            <v-btn
                class="my-2 white--text text-decoration-none"
                :color="post.bookmarked_by_user === true ? 'grey' : 'orange'"
                @click="onBookmarkClick"
            >bookmark
                <v-icon color="white">bookmark</v-icon>
            </v-btn>
        </div>
        <div class="text-right">{{ post.updated_at }} 更新</div>
        <h1>{{ post.post_title }}</h1>
        <div class="text-right">
            posted_by
            <v-avatar size="30px">
                <img :src="post.user.url">
            </v-avatar>
            {{ post.user.name }}
        </div>
        <PostPhoto :photos="post.photos" class="mt-5"></PostPhoto>
        <v-row justify="center">
            <v-col
            cols="12"
            sm="8"
            md="6"
            >
                <v-card
                    shaped
                    class="mt-5"
                >
                    <v-card-title class="text-center">about</v-card-title>
                    <v-card-text>{{ post.about }}</v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <h2 class="text-center mt-10">Messages</h2>
        <v-divider ></v-divider>
        <MessageList
            :id="this.id">
        </MessageList>
    </div>
</template>

<script>
import { OK } from '../util'
import MessageModal from '../components/MessageModal.vue'
import PostPhoto from '../components/PostPhoto.vue'
import MessageList from '../components/MessageList.vue'

export default {
    components: {
        MessageModal,
        PostPhoto,
        MessageList
    },
    props: {
        id: {
            type: String,
            required: true
        }
    },
    data () {
        return {
            post: null,
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
        },
        onBookmarkClick () {
            if (! this.isLogin) {
                alert('ブックマーク機能を使うにはログインが必要です。')
                return false
            }

            if (this.post.bookmarked_by_user) {
                this.deleteBookmark()
            } else {
                this.bookmark()
            }
        },
        async bookmark () {
            const response = await axios.put(`/api/post/${this.id}/bookmark`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.post.bookmarked_by_user = true
        },
        async deleteBookmark () {
            const response = await axios.delete(`/api/post/${this.id}/bookmark`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.post.bookmarked_by_user = false
        }
    },
    computed: {
        isLogin () {
            return this.$store.getters['auth/check']
        },
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