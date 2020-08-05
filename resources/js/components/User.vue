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
        id: {
            type: Number,
            required: true
        }
    },
    data () {
        return {
            user: '',
            dialog: false,
        }
    },
    methods: {
        async fetchUser () {
            const response = await axios.get(`/api/myuser/${this.id}`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.user = response.data
        },
        updateUser ({ login_id, name}) {
            this.update(login_id, name)
        },
        async update (login_id, name) {
            const response = await axios.put('/api/user', {
                login_id: login_id,
                name: name,
            })

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            await this.fetchUser()
        },
        showDialog () {
            this.$refs.dialog.open()
        },
    },
    watch: {
        $route: {
            async handler () {
                await this.fetchUser()
            },
            immediate: true
        }
    }
}
</script>