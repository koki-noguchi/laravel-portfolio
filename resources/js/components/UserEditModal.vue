<template>
    <v-row justify="center">
        <v-dialog v-model="dialog" width="550">
            <v-card>
                <v-card-title class="justify-center">User Edit</v-card-title>
                    <v-form
                        @submit.prevent="updateUser"
                        ref="form"
                        v-model="valid"
                    >
                        <div v-if="errors" class="errors red--text">
                            <ul v-if="errors.login_id">
                                <li v-for="msg in errors.login_id" :key="msg">{{ msg }}</li>
                            </ul>
                            <ul v-if="errors.name">
                                <li v-for="msg in errors.name" :key="msg">{{ msg }}</li>
                            </ul>
                            <ul v-if="errors.user_image">
                                <li v-for="msg in errors.user_image" :key="msg">{{ msg }}</li>
                            </ul>
                        </div>
                        <v-text-field
                            filled
                            v-model="user.login_id"
                            clearable
                            label="login_id"
                            class="ma-5"
                            :rules="[rules.required, rules.min]"
                            :disabled="user.login_id === 'guest001'"
                        >
                        </v-text-field>
                        <v-text-field
                            filled
                            v-model="user.name"
                            clearable
                            label="name"
                            class="ma-5"
                            :rules="[rules.required]"
                            :disabled="user.login_id === 'guest001'"
                        ></v-text-field>
                        <ProfileImage
                            :imgSrc="user.user_image"
                            :login_id="user.login_id"
                            @setUserPhoto="setUserPhoto"
                        ></ProfileImage>
                        <v-card-actions class="justify-center">
                            <v-btn
                                type="submit"
                                width="160"
                                class="mb-5 mt-5 white--text"
                                color="pink lighten-1"
                                @click.stop="close"
                                :disabled="!valid || user.login_id === 'guest001'"
                            >送信</v-btn>
                        </v-card-actions>
                        <v-card-actions class="justify-end">
                        <v-btn
                            width="60"
                            class="mb-5"
                            outlined
                            color="pink lighten"
                            @click="dialog2 = !dialog2"
                            :disabled="user.login_id === 'guest001'"
                        >退会する</v-btn>
                    </v-card-actions>
                </v-form>
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
        },
        errors: {
            type: Object
        }
    },
    data () {
        return {
            login_id: '',
            name: '',
            dialog: false,
            dialog2: false,
            valid: true,
            rules: {
                required: value => !!value || '必須項目です。',
                min: v => (v && v.length >= 8) || '8文字以上入力してください。',
            }
        }
    },
    methods: {
        async updateUser () {
            if (!this.$refs.form.validate()) {
                return false
            }

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
        setUserPhoto ({ user_image }) {
            this.$emit('setUserPhoto', {
                user_image: user_image
            })
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