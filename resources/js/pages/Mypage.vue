<template>
    <div>
        <User :id="myId"></User>
        <v-tabs
            centered
            class="mt-5"
        >
            <v-tab href="#history">
            History
            </v-tab>
            <v-tab-item id="history">
                <Post
                    v-for="history in histories"
                    :key="history.id"
                    :item="history"
                    @bookmark="onBookmarkClick"
                ></Post>
            </v-tab-item>
            <v-tab href="#bookmark">
            Bookmark
            </v-tab>
            <v-tab-item id="bookmark">
                <Post
                    v-for="bookmark in bookmarks"
                    :key="bookmark.id"
                    :item="bookmark"
                    @bookmark="onBookmarkClick"
                ></Post>
            </v-tab-item>
        </v-tabs>
    </div>
</template>

<script>
import { OK } from '../util'
import Post from '../components/Post.vue'
import User from '../components/User.vue'

export default {
    components: {
        Post,
        User
    },
    data () {
        return {
            histories: null,
            bookmarks: null
        }
    },
    methods: {
        async fetchHistory () {
            const response = await axios.get('/api/history')

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.histories = response.data
        },
        async fetchBookmark () {
            const response = await axios.get('/api/user/bookmark')

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.bookmarks = response.data
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
                return false
            }

            this.histories = this.histories.map(history => {
                if (history.id === response.data.post_id) {
                    history.bookmarked_by_user = true
                }
                this.fetchBookmark()
                return history
            })
        },
        async deleteBookmark (id) {
            const response = await axios.delete(`/api/post/${id}/bookmark`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.histories = this.histories.map(history => {
                if (history.id === response.data.post_id) {
                    history.bookmarked_by_user = false
                }
                this.fetchBookmark()
                return history
            })
        },
    },
    computed: {
        myId () {
            return this.$store.getters['auth/id']
        }
    },
    watch: {
        $route: {
            async handler () {
                await this.fetchHistory()
                await this.fetchBookmark()
            },
            immediate: true
        }
    }
}
</script>