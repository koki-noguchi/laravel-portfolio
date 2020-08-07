<template>
    <v-row justify="center">
        <v-card max-width="500" class="mt-10">
            <v-row>
                <v-col cols="12">
                    <v-layout justify-center class="red lighten-3">
                        <v-avatar size="50px" class="mt-5">
                            <v-img
                                :src="user.url"
                            ></v-img>
                        </v-avatar>
                    </v-layout>
                    <v-layout justify-center class="red lighten-3">
                        <v-card-title
                            class="text-wrap justify-center text-white"
                        >
                            {{ user.name }}
                        </v-card-title>
                        <v-card-actions>
                            <v-btn
                                class="ma-2"
                                icon
                                color="white"
                                @click.stop="showDialog"
                            >
                                <v-icon size="30">edit</v-icon>
                            </v-btn>
                        </v-card-actions>
                    </v-layout>
                    <v-card-actions class="justify-center mt-2">
                        <v-btn class="ml-5 mr-2">0 follow</v-btn>
                        <v-btn class="mr-5 ml-2">0 follower</v-btn>
                    </v-card-actions>
                </v-col>
                <UserEditModal
                    ref="dialog"
                    :user="user"
                    @updateUser="updateUser"
                    @setUserPhoto="setUserPhoto"
                ></UserEditModal>
            </v-row>
        </v-card>
    </v-row>
</template>

<script>
import { OK } from "../util"
import UserEditModal from "../components/UserEditModal.vue"

export default {
    components: {
        UserEditModal
    },
    props: {
        user: {
            type:Object,
            required: true
        }
    },
    data () {
        return {
            dialog: false,
        }
    },
    methods: {
        updateUser ({ login_id, name}) {
            this.$emit('updateUser', {
                login_id: login_id,
                name: name,
            })
        },
        showDialog () {
            this.$refs.dialog.open()
        },
        setUserPhoto ({ user_image }) {
            this.$emit('setUserPhoto', {
                user_image: user_image
            })
        },
    },
}
</script>