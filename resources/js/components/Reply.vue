<template>
    <v-row>
        <v-card
            shaped
            class="my-5">
            <v-card-text
                class="text-body-1 black--text"
            >
                {{ reply.reply_text }}
            </v-card-text>
            <v-card-actions>
                <v-list-item>
                    <router-link :to="`/users/${reply.reply_user.id}`">
                        <v-list-item-avatar size="30px">
                            <v-img
                                :src="reply.reply_user.url"
                            ></v-img>
                        </v-list-item-avatar>
                    </router-link>
                    <v-list-item-content>
                        <v-list-item-title
                            class="text-body-2 text-wrap">
                            {{ reply.reply_user.name }}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-card-actions>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    v-if="reply.my_reply"
                    icon
                    class="mr-1"
                    @click.stop="dialog = true"
                >
                    <v-icon>delete</v-icon>
                </v-btn>
            </v-card-actions>
        </v-card>
        <v-dialog
            v-model="dialog"
            max-width="400"
        >
            <v-card>
                <v-card-title class="headline">返信を削除してもよろしいですか？</v-card-title>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                    color="green darken-1"
                    text
                    @click="dialog = false"
                    @click.prevent="deleteReply(reply.id)"
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
    </v-row>
</template>

<script>
export default {
    props: {
        reply: {
            type: Object,
            required: true
        }
    },
    data () {
        return {
            dialog: false
        }
    },
    methods: {
        deleteReply (id) {
            this.$emit('deleteReply', {
                id: id
            })
        },
        showDialog () {
            this.$refs.dialog.open()
        }
    }
}
</script>