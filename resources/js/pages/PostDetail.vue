<template>
    <div v-if="post" class="post-detail">
        <div class="text-right">
            <v-btn
                v-if="post.my_post"
                class="ma-2 white--text text-decoration-none"
                color="blue lighten-2"
                :to="`/post/${id}/edit`"
            >編集
                <v-icon color="white">edit</v-icon>
            </v-btn>
            <v-btn
                class="my-2 white--text text-decoration-none"
                :color="post.bookmarked_by_user === true ? 'grey' : 'orange'"
                @click="onBookmarkClick"
            >ブックマーク
                <v-icon color="white">bookmark</v-icon>
            </v-btn>
        </div>
        <div class="text-right">{{ post.updated_at }} 更新</div>
        <h1>{{ post.post_title }}</h1>
        <div class="text-right">
            posted_by
            <router-link :to="`/users/${post.user.id}/history`">
                <v-avatar size="30px">
                    <img :src="post.user.url">
                </v-avatar>
            </router-link>
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
                    <v-card-title class="text-center">概要</v-card-title>
                    <v-card-text>{{ post.about }}</v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <h2 class="text-center mt-10">メッセージ</h2>
        <v-divider ></v-divider>
        <MessageList
            :id="this.id"
            :messages="this.post.messages"
            :limit_judge="this.post.limit_judge"
            :errors="errors"
            @on-click-btn="onClickBtn($event)"
            @delete-message="deleteMessage"
            @create-message="createMessage">
        </MessageList>
    </div>
</template>

<script>
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
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
            dialog: false,
            errors: null,
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
                this.$store.commit('message/setErrorContent', {
                    errorContent: 'エラーが発生しました。',
                })
                return false
            }

            this.$store.commit('message/setSuccessContent', {
                successContent: 'ブックマークしました。',
            })
            this.post.bookmarked_by_user = true
        },
        async deleteBookmark () {
            const response = await axios.delete(`/api/post/${this.id}/bookmark`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                this.$store.commit('message/setErrorContent', {
                    errorContent: 'エラーが発生しました。',
                })
                return false
            }

            this.$store.commit('message/setSuccessContent', {
                successContent: 'ブックマークを外しました。',
            })
            this.post.bookmarked_by_user = false
        },
        async createMessage ({ text }) {
            const response = await axios.post(`/api/post/${this.id}/messages`,
            {
                message_text: text
            })

            if (response.status === UNPROCESSABLE_ENTITY) {
                this.errors = response.data.errors
                this.$store.commit('message/setErrorContent', {
                    errorContent: 'エラーが発生しました。',
                })
                return false
            }

            if (response.status !== CREATED) {
                this.$store.commit('error/setCode', response.status)
                this.$store.commit('message/setErrorContent', {
                    errorContent: 'エラーが発生しました。',
                })
                return false
            }

            this.$store.commit('message/setSuccessContent', {
                successContent: '送信しました。',
            })
            this.fetchPost()
        },
        async deleteMessage ({ message_id }) {
            const response = await axios.delete(`/api/message/${message_id}`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                this.$store.commit('message/setErrorContent', {
                    errorContent: 'エラーが発生しました。',
                })
                return false
            }

            this.$store.commit('message/setSuccessContent', {
                successContent: '削除しました。',
            })
            this.fetchPost()
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