<template>
    <div>
        <v-row justify="center" class="my-10">
            <v-card class="text-decoration-none" width="200">
                <v-row class="text-body-2 pt-2">
                    <v-col>
                        <v-card-actions class="justify-center ml-7">
                            <v-list-item-avatar size="30">
                                <v-img :src="user.url"></v-img>
                            </v-list-item-avatar>
                            <v-list-item-title
                                class="text-body-2 text-wrap"
                            >
                                {{ user.name }}
                            </v-list-item-title>
                        </v-card-actions>
                    </v-col>
                </v-row>
            </v-card>
        </v-row>
        <v-tabs
            centered
            class="mt-5"
        >
            <v-tab key="follow" :to="`/users/${this.id}/follow`" :id="this.id">FollowList</v-tab>
            <v-tab key="follower" :to="`/users/${this.id}/follow/follower`" :id="this.id">FollowerList</v-tab>
        </v-tabs>
        <router-view :key="$route.path"></router-view>
    </div>
</template>

<script>
import Follow from '../components/Follow.vue'
import { OK } from '../util'

export default {
   components: {
        Follow
    },
    props: {
        id: {
            type: String,
            required: true
        }
    },
    data () {
        return {
            user: {},
            tab: `/users/${this.id}/follow`,
        }
    },
    methods: {
        async fetchProfile () {
            const response = await axios.get(`/api/users/${this.id}`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.user = response.data
        }
    },
    watch: {
        $route: {
            async handler () {
                await this.fetchProfile()
            },
            immediate: true
        }
    }
}
</script>