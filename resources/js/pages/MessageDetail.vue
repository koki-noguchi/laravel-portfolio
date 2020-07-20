<template>
    <div v-if="message" class="message-detail">
        <div class="message-detail__content">
            {{ message.message_text }}
        </div>
        <div>
            {{ message.author.name }}
        </div>
        <ul v-if="message.replies.length > 0">
            <li
              v-for="reply in message.replies"
              :key="reply.reply_text"
              class="message-detail__replyItem">
                <div class="message-detail__content">
                    {{ reply.reply_text }}
                </div>
                <div class="message-detail__name">
                    {{ reply.reply_user.name }}
                </div>
            </li>
        </ul>
        <div>
            <MessageModal
              @create="createReply"
            ></MessageModal>
        </div>

    </div>
</template>

<script>
import { OK } from '../util'
import MessageModal from '../components/MessageModal.vue'

export default {
    components: {
        MessageModal,
    },
    props: {
        post_id: {
            type: String,
            required: true
        },
        message_id: {
            type: String,
            required: true
        }
    },
    data () {
        return {
            message: null,
        }
    },
    methods: {
        async fetchReply () {
            const response = await axios.get(`/api/post/${this.post_id}/message/${this.message_id}`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.message = response.data
        },
        createReply ({ text }) {
            if (! this.isLogin) {
                alert('リプライ機能を使うにはログインが必要です。')
                return false
            }
            this.create (text)
        },
        async create (text) {
            const response = await axios.post(`/api/post/${this.post_id}/message/${this.message_id}`, {
                reply_text: text
            })

            this.message.replies = [
                response.data,
                ...this.message.replies
            ]
        }
    },
    computed: {
        isLogin () {
            return this.$store.getters['auth/check']
        }
    },
    watch: {
        $route: {
            async handler () {
                await this.fetchReply()
            },
            immediate: true
        }
    }
}
</script>