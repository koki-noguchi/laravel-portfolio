<template>
    <div>
        <Post
            v-for="history in histories"
            :key="history.id"
            :item="history"
            @bookmark="onBookmarkClick"
        ></Post>
        <Pagination :id="id" :page="page" :last-page="lastPage" @next="next" />
    </div>
</template>

<script>
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
import Post from '../components/Post.vue'
import Pagination from '../components/Pagination.vue'

export default {
    components: {
        Post,
        Pagination
    },
    props: {
        id: {
            type: String,
            required: true
        },
    },
    data () {
        return {
            histories: null,
            page: 1,
            lastPage: 0,
        }
    },
    methods: {
        async fetchHistory () {
            const response = await axios.get(`/api/users/${this.id}/history/?page=${this.page}`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.histories = response.data.data
            this.lastPage = response.data.last_page
        },
        getPage () {
            this.page = Number(this.$route.query.page)
            if (! this.page) {
                this.page = 1
            }
        },
        next ({page}) {
            this.$router.push(`/users/${this.id}/history/?page=${page}`)
        },
        onBookmarkClick ({id, bookmarked_by_user}) {
            if (bookmarked_by_user) {
                this.deleteBookmark(id)
            } else {
                this.bookmark(id)
            }
        },
        async bookmark (id) {
            const response = await axios.put(`/api/post/${id}/bookmark`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                this.$store.commit('message/setErrorContent', {
                    errorContent: 'エラーが発生しました。',
                })
                return false
            }

            this.histories = this.histories.map(history => {
                if (history.id === response.data.post_id) {
                    history.bookmarked_by_user = true
                }

                return history
            })

            this.$store.commit('message/setSuccessContent', {
                successContent: 'ブックマークしました。',
            })
        },
        async deleteBookmark (id) {
            const response = await axios.delete(`/api/post/${id}/bookmark`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                this.$store.commit('message/setErrorContent', {
                    errorContent: 'エラーが発生しました。',
                })
                return false
            }

            this.histories = this.histories.map(history => {
                if (history.id === response.data.post_id) {
                    history.bookmarked_by_user = false
                }

                    return history
                })
            this.$store.commit('message/setSuccessContent', {
                successContent: 'ブックマークを外しました。',
            })
        },
    },
    watch: {
        $route: {
            async handler () {
                this.getPage()
                await this.fetchHistory()
            },
            immediate: true
        }
    }
}
</script>