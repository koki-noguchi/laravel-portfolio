<template>
    <v-row class="mt-12">
        <v-col
            cols="12"
            sm="12"
            md="4"
            v-for="(message, i) in messages"
            :key="i"
        >
            <v-card>
                <v-card-text class="text-body-1 black--text">{{ message.message_text }}</v-card-text>
                <v-card-actions>
                    <v-list-item>
                        <v-list-item-avatar size="30px">
                            <v-img
                                :src="message.author.url"
                            ></v-img>
                        </v-list-item-avatar>
                        <v-list-item-content>
                            <v-list-item-title class="text-body-2 text-wrap">{{ message.author.name }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-card-actions>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        icon
                        :to="`/post/${id}/message/${message.id}`"
                        class="text-decoration-none mr-1">
                        <v-icon>add_comment</v-icon>
                    </v-btn>
                    <v-btn icon @click.prevent="deleteMessage(message.id)"
                        class="mr-1">
                        <v-icon>delete</v-icon>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-col>
        <v-btn
            fixed
            dark
            fab
            bottom
            right
            color="pink"
            class="mb-12"
            @click.stop="showDialog"
        >
            <v-icon>mdi-plus</v-icon>
        </v-btn>
        <Message-modal
            ref="dialog"
            @create="createMessage"
        ></Message-modal>
    </v-row>
</template>

<script>
import { OK } from '../util'
import MessageModal from '../components/MessageModal.vue'

export default {
    components: {
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
            messages: null
        }
    },
    methods: {
        async fetchMessages () {
            const response = await axios.get(`/api/post/${this.id}`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.messages = response.data.messages
        },
        createMessage ({ text }) {
            this.create(text)
        },
        async create (text) {
            const response = await axios.post(`/api/post/${this.id}/messages`,
            {
                message_text: text
            })

            this.fetchMessages()
        },
        async deleteMessage (id) {
            const response = await axios.delete(`/api/message/${id}`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.fetchMessages()
        },
        showDialog () {
            this.$refs.dialog.open()
        }
    },
    watch: {
        $route: {
            async handler () {
                await this.fetchMessages()
            },
            immediate: true
        }
    }
}
</script>