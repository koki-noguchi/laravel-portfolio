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
            <v-tab href="#follower">
            Follower
            </v-tab>
            <v-tab-item id="follower">
                <Follow
                    v-for="follower in followers"
                    :key="follower.id"
                    :item="follower"
                    @followBtnClick="followerBtnClick"
                ></Follow>
            </v-tab-item>
            <v-tab href="#follow">
            Follow
            </v-tab>
            <v-tab-item id="follow">
                <Follow
                    v-for="follow in follows"
                    :key="follow.id"
                    :item="follow"
                    @followBtnClick="followBtnClick"
                ></Follow>
            </v-tab-item>
        </v-tabs>
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
            follows: '',
            followers: '',
            user: {
                url: '',
                name: ''
            },
        }
    },
    methods: {
        async fetchFollow () {
            const response = await axios.get(`/api/users/${this.id}/follow`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.follows = response.data.followings
            this.followers = response.data.followers
            this.user.url = response.data.url
            this.user.name = response.data.name
        },
        followBtnClick ({id, followed_judge}) {
            if (followed_judge) {
                this.deleteFollow(id)
            } else {
                this.follow(id)
            }
        },
        async follow (id) {
            const response = await axios.put(`/api/users/${id}/follow`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.follows = this.follows.map(follow => {
                if (follow.id === response.data.followee_id) {
                    follow.followed_judge = true
                }
                return follow
            })
        },
        async deleteFollow (id) {
            const response = await axios.delete(`/api/users/${id}/follow`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.follows = this.follows.map(follow => {
                if (follow.id === response.data.followee_id) {
                    follow.followed_judge = false
                }
                return follow
            })
        },
        followerBtnClick ({id, followed_judge}) {
            if (followed_judge) {
                this.followerDeleteFollow(id)
            } else {
                this.followerFollow(id)
            }
        },
        async followerFollow (id) {
            const response = await axios.put(`/api/users/${id}/follow`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.followers = this.followers.map(follower => {
                if (follower.id === response.data.followee_id) {
                    follower.followed_judge = true
                }
                return follower
            })
        },
        async followerDeleteFollow (id) {
            const response = await axios.delete(`/api/users/${id}/follow`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.followers = this.followers.map(follower => {
                if (follower.id === response.data.followee_id) {
                    follower.followed_judge = false
                }
                return follower
            })
        },
    },
    watch: {
        $route: {
            async handler () {
                await this.fetchFollow()
            },
            immediate: true
        }
    }
}
</script>