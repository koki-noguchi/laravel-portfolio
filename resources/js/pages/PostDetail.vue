<template>
    <div v-if="post" class="post-detail">
        <button
          class="button button--bookmark"
          :class="{ 'button--bookmark': post.bookmarked_by_user }"
          title="Bookmark post"
          @click="onBookmarkClick"
        >
            <i class="icon ion-md-heart">bookmark</i>
        </button>
        <h1 class="post-detail__title">{{ post.post_title }}</h1>
        <div class="post-detail__about">
            {{ post.about }}
        </div>
        <div class="post-detail__name">
            {{ post.user.name}}
        </div>
        <button @click.prevent="deletePost">募集ページの削除</button>
        <h2 class="post-detail__title">Messages</h2>
        <ul v-if="post.messages.length > 0" class="post-detail__messages">
            <li
              v-for="message in post.messages"
              :key="message.message_text"
              class="post-detail__messageItem"
            >
            <p class="post-detail__messageBody">
                {{ message.message_text }}
            </p>
            <p class="post-detail__messageBody">
                {{ message.author.name}}
            </p>
            </li>
        </ul>
        <p v-else>No messages yet.</p>
        <button >edit</button>
        <PostModal
          :post="post"
          @update="updatePost"
        ></PostModal>
        <button>message</button>
        <MessageModal
          @create="createMessage"
        ></MessageModal>
    </div>
</template>

<script>
import { OK } from '../util'
import PostModal from '../components/PostModal.vue'
import MessageModal from '../components/MessageModal.vue'

export default {
    components: {
        PostModal,
        MessageModal
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
        async deletePost () {
            const response = await axios.delete(`/api/post/${this.id}`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.$router.push('/post')
        },
        updatePost ({ post_title, about }) {
            this.update(post_title, about)
        },
        async update (post_title, about) {
            const response = await axios.put(`/api/post/${this.id}`,
            {
                post_title: post_title,
                about: about
            })
            await this.fetchPost()
        },
        createMessage ({ message_text }) {
            this.create(message_text)
        },
        async create (message_text) {
            const response = await axios.post(`/api/post/${this.id}/messages`,
            {
                message_text: message_text
            })

            this.post.messages = [
                response.data,
                ...this.post.messages
            ]
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