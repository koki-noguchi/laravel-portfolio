<template>
    <v-row justify="center" v-if="message">
        <v-col
            cols="12"
            sm="8"
            md="6"
        >
            <v-card>
                <v-card-text
                    class="text-body-1 black--text"
                >
                    {{ message.message_text }}
                </v-card-text>
                <v-card-actions>
                    <v-list-item>
                        <router-link :to="`/users/${message.author.id}`">
                            <v-list-item-avatar size="30px">
                                <v-img
                                    :src="message.author.url"
                                ></v-img>
                            </v-list-item-avatar>
                        </router-link>
                        <v-list-item-content>
                            <v-list-item-title
                                class="text-body-2 text-wrap">
                                {{ message.author.name }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-card-actions>
            </v-card>
            <v-btn
                v-if="isLogin"
                dark
                fab
                center
                color="pink"
                class="mb-12 mt-5"
                @click.stop="showDialog"
            >
                <v-icon>insert_comment</v-icon>
            </v-btn>
            <h2 class="text-center mt-10">返信</h2>
            <v-divider ></v-divider>
            <Message-modal
                ref="dialog"
                @create="createReply"
            >返信</Message-modal>
            <Reply
                v-for="reply in message.replies"
                :key="reply.id"
                :reply="reply"
                @deleteReply="deleteReply"
            ></Reply>
        </v-col>
    </v-row>
</template>

<script>
import { OK } from '../util'
import MessageModal from '../components/MessageModal.vue'
import Reply from '../components/Reply.vue'

export default {
    components: {
        MessageModal,
        Reply
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

            this.$store.commit('message/setSuccessContent', {
                successContent: '返信しました。',
            })
        },
        deleteReply ({ id }) {
            this.delete(id)
        },
        async delete (id) {
            const response = await axios.delete(`/api/reply/${id}`)

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
            this.fetchReply()
        },
        showDialog () {
            this.$refs.dialog.open()
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