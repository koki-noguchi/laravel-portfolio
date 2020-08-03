<template>
    <v-row justify="center">
        <v-dialog v-model="dialog" width="550">
            <v-card>
                <v-card-title class="justify-center">User Edit</v-card-title>
                <v-text-field
                    filled
                    v-model="user.login_id"
                    clearable
                    label="login_id"
                    class="ma-5"
                >
                </v-text-field>
                <v-text-field
                    filled
                    v-model="user.name"
                    clearable
                    label="name"
                    class="ma-5"
                ></v-text-field>
                <ProfileImage :imgSrc="user.user_image"></ProfileImage>
                <v-card-actions class="justify-center">
                    <v-btn
                        width="160"
                        class="mb-5 mt-3"
                        outlined
                        color="pink lighten-1"
                        @click.prevent="updateUser"
                        @click="close"
                    >送信</v-btn>
                </v-card-actions>
                <v-card-actions class="justify-end">
                <v-btn
                    width="60"
                    class="mb-5 white--text"
                    color="red"
                    @click.stop="dialog2 = !dialog2"
                >退会する</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialog2" width="350">
            <v-card>
                <v-card-title class="headline">本当にアカウントを削除してもよろしいですか？</v-card-title>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                    color="green darken-1"
                    text
                    @click="dialog2 = false"
                    @click.prevent="deleteUser"
                    >
                    Yes
                    </v-btn>
                    <v-btn
                    color="red darken-1"
                    text
                    @click="dialog2 = false"
                    >
                    No
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import { OK } from "../util"
import ProfileImage from '../components/ProfileImage.vue'

export default {
    components: {
        ProfileImage
    },
    props: {
        user: {
            required: true
        }
    },
    data () {
        return {
            login_id: '',
            name: '',
            dialog: false,
            dialog2: false,
        }
    },
    methods: {
        async updateUser () {
            this.$emit('updateUser', {
                login_id: this.user.login_id,
                name: this.user.name
            })
        },
        async deleteUser () {
            const response = await axios.delete('/api/user')

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }
            this.$store.commit('auth/setUser', null)
            this.$router.push('/')
        },
        open () {
            this.dialog = true
        },
        close () {
            this.dialog = false
        }
    }
}
</script>