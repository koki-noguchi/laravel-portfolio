<template>
    <div>
        <div class="text-right" v-if="!isMyAccount && isLogin">
            <v-btn
                class="ma-2 white--text text-decoration-none"
                :color="user.followed_judge === true ? 'grey' : 'blue'"
                @click="followBtnClick">フォロー
            </v-btn>
        </div>
        <User
            :user="user"
            :errors="errors"
            @updateUser="updateUser"
            @setUserPhoto="setUserPhoto"
        ></User>
        <v-tabs
            centered
            class="mt-5 my-3"
            v-model="tab"
        >
            <v-tab key="timeline" :to="`/users/${this.id}/timeline`" v-if="isMyAccount">タイムライン</v-tab>
            <v-tab key="history" :to="`/users/${this.id}/history`" :id="this.id">履歴</v-tab>
            <v-tab key="bookmark" :to="`/users/${this.id}/bookmark`" v-if="isMyAccount">ブックマーク</v-tab>
        </v-tabs>
        <router-view :key="$route.path"></router-view>
    </div>
</template>

<script>
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
import User from '../components/User.vue'
import Timeline from '../components/Timeline.vue'
import History from '../components/History.vue'
import Bookmark from '../components/Bookmark.vue'

export default {
    components: {
        User,
        Timeline,
        History,
        Bookmark,
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
            errors: null,
            account_check: false,
            tab: `/users/${this.id}/timeline`,
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
        },
        async updateUser ({ login_id, name }) {
            const response = await axios.put('/api/user', {
                login_id: login_id,
                name: name,
            })

            if (response.status === UNPROCESSABLE_ENTITY) {
                this.errors = response.data.errors
                this.$store.commit('message/setErrorContent', {
                    errorContent: 'エラーが発生しました。',
                })
                return false
            }

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                this.$store.commit('message/setErrorContent', {
                    errorContent: 'エラーが発生しました。',
                })
                return false
            }

            this.$store.commit('message/setSuccessContent', {
                successContent: 'プロフィールを変更しました。',
            })
        },
        async setUserPhoto ({ user_image }) {
            const response = await axios.put('/api/user', {
                user_image: user_image
            })

            if (response.status === UNPROCESSABLE_ENTITY) {
                this.errors = response.data.errors
                this.$store.commit('message/setErrorContent', {
                    errorContent: 'エラーが発生しました。',
                })
                return false
            }

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                this.$store.commit('message/setErrorContent', {
                    errorContent: 'エラーが発生しました。',
                })
                return false
            }

            this.user.url = response.data.url
            this.$store.commit('message/setSuccessContent', {
                successContent: 'プロフィール画像を変更しました',
            })
        },
        followBtnClick () {
            if (this.user.followed_judge) {
                this.deleteFollow()
            } else {
                this.follow()
            }
        },
        async follow () {
            const response = await axios.put(`/api/users/${this.id}/follow`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                this.$store.commit('message/setErrorContent', {
                    errorContent: 'エラーが発生しました。',
                })
                return false
            }
            this.user.follower_count += 1
            this.user.followed_judge = true

            this.$store.commit('message/setSuccessContent', {
                successContent: 'フォローしました。',
            })
        },
        async deleteFollow () {
            const response = await axios.delete(`/api/users/${this.id}/follow`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                this.$store.commit('message/setErrorContent', {
                    errorContent: 'エラーが発生しました。',
                })
                return false
            }

            this.user.follower_count -= 1
            this.user.followed_judge = false

            this.$store.commit('message/setSuccessContent', {
                successContent: 'フォローを外しました。',
            })
        }
    },
    computed: {
        isMyAccount () {
            const check = String(this.$store.getters['auth/id']) === this.id
            this.account_check = check
            return check
        },
        isLogin () {
            return this.$store.getters['auth/check']
        },
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