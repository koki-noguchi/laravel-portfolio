<template>
    <div>
        <Follow
            v-for="follow in follows"
            :key="follow.id"
            :item="follow"
            @followBtnClick="followBtnClick"
        ></Follow>
    </div>
</template>

<script>
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
import Follow from '../components/Follow.vue'

export default {
    components: {
        Follow,
    },
    props: {
        id: {
            type: String,
            required: true
        }
    },
    data () {
        return {
            follows: null,
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

            this.follows = this.follows.map(follow => {
                if (follow.id === response.data.followee_id) {
                    follow.followed_judge = true
                }
                return follow
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

            this.follows = this.follows.map(follow => {
                if (follow.id === response.data.followee_id) {
                    follow.followed_judge = false
                }
                return follow
            })

            this.$store.commit('message/setSuccessContent', {
                successContent: 'フォローを外しました。',
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