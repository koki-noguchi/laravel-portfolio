<template>
    <div>
        <Follower
            v-for="follower in followers"
            :key="follower.id"
            :item="follower"
            @followBtnClick="followBtnClick"
        ></Follower>
    </div>
</template>

<script>
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
import Follower from '../components/Follow.vue'

export default {
    components: {
        Follower,
    },
    props: {
        id: {
            type: String,
            required: true
        }
    },
    data () {
        return {
            followers: null,
        }
    },
    methods: {
        async fetchFollower () {
            const response = await axios.get(`/api/users/${this.id}/follower`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.followers = response.data.followers
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
                this.$store.commit('message/setErrorContent', {
                    errorContent: 'エラーが発生しました。',
                })
                return false
            }

            this.followers = this.followers.map(follower => {
                if (follower.id === response.data.followee_id) {
                    follower.followed_judge = true
                }
                return follower
            })

            this.$store.commit('message/setSuccessContent', {
                successContent: 'フォローしました。',
            })
        },
        async deleteFollow (id) {
            const response = await axios.delete(`/api/users/${id}/follow`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                this.$store.commit('message/setErrorContent', {
                    errorContent: 'エラーが発生しました。',
                })
                return false
            }

            this.followers = this.followers.map(follower => {
                if (follower.id === response.data.followee_id) {
                    follower.followed_judge = false
                }
                return follower
            })

            this.$store.commit('message/setSuccessContent', {
                successContent: 'フォローを外しました。',
            })
        },
    },
    watch: {
        $route: {
            async handler () {
                await this.fetchFollower()
            },
            immediate: true
        }
    }
}
</script>