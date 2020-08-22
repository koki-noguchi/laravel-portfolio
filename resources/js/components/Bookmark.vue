<template>
    <div>
        <Post
            v-for="bookmark in bookmarks"
            :key="bookmark.id"
            :item="bookmark"
            @bookmark="onBookmarkClick"
        ></Post>
    </div>
</template>

<script>
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
import Post from '../components/Post.vue'

export default {
    components: {
        Post,
    },
    data () {
        return {
            bookmarks: null,
        }
    },
    methods: {
        async fetchBookmarks () {
            const response = await axios.get(`/api/users/bookmark`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.bookmarks = response.data.data
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

            this.bookmarks = this.bookmarks.map(bookmark => {
                if (bookmark.id === response.data.post_id) {
                    bookmark.bookmarked_by_user = true
                }

                return bookmark
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

            this.bookmarks.filter((bookmark, i) => {
                    if (bookmark.id === response.data.post_id) {
                        bookmark.bookmarked_by_user = false
                        this.bookmarks.splice(i, 1)
                    }
                    return bookmark
                })

            this.$store.commit('message/setSuccessContent', {
                successContent: 'ブックマークを外しました。',
            })
        },
    },
    watch: {
        $route: {
            async handler () {
                await this.fetchBookmarks()
            },
            immediate: true
        }
    }
}
</script>