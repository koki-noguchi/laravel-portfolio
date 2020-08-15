<template>
    <v-row class="mt-12">
        <v-col cols="12" class="text-center" v-if="messages.length < 1">
            <strong class="orange--text">No messages yet.</strong>
        </v-col>
        <v-col
            cols="12"
            sm="12"
            md="4"
        >
            <v-card
                v-for="message in messages"
                :key="message.id"
            >
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
                <v-card-actions>
                    <v-layout>
                    <v-btn
                        icon
                        :to="`/post/${id}/message/${message.id}`"
                        class="text-decoration-none ml-3">
                        <v-icon>add_comment</v-icon>
                    </v-btn>
                    <div v-if="message.replies_count > 0" class="mt-3">
                        {{ message.replies_count }}
                    </div>
                    </v-layout>
                    <v-spacer></v-spacer>
                    <v-btn
                        v-if="message.my_message"
                        icon
                        class="mr-1"
                        @click.stop="onClickBtn(message.id)"
                    >
                        <v-icon>delete</v-icon>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-col>
        <v-dialog
            v-model="dialog"
            max-width="400"
            >
                <v-card>
                    <v-card-title class="headline">メッセージを削除してもよろしいですか？</v-card-title>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                        color="green darken-1"
                        text
                        @click="dialog = false"
                        @click.stop="deleteMessage(message_id)"
                        >
                        Yes
                        </v-btn>
                        <v-btn
                        color="red darken-1"
                        text
                        @click="dialog = false"
                        >
                        No
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        <v-btn
            v-if="limit_judge !== true && isLogin"
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
            :errors="errors"
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
        },
        messages: {
            type: Array,
            required: true
        },
        limit_judge: {
            required: true
        },
        errors: {
            type: Object
        }
    },
    data () {
        return {
            dialog: false,
            message_id: '',
        }
    },
    computed: {
        isLogin () {
            return this.$store.getters['auth/check']
        },
    },
    methods: {
        createMessage ({ text }) {
            this.$emit('create-message', {
                text: text
            })
        },
        onClickBtn (id) {
            this.message_id = id
            this.dialog = true
        },
        deleteMessage (message_id) {
            this.$emit('delete-message', {
                message_id: message_id
            })
        },
        showDialog () {
            this.$refs.dialog.open()
        },
    },
}
</script>