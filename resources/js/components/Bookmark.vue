<template>
    <div>
        <Post
            v-for="bookmark in bookmarks"
            :key="bookmark.id"
            :item="bookmark"
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
        Pagination,
    },
    props: {
        id: {
            type: String,
            required: true
        }
    },
    data () {
        return {
            bookmarks: null,
            page: 1,
            lastPage: 0,
        }
    },
    methods: {
        async fetchBookmarks () {
            const response = await axios.get(`/api/users/bookmark/?page=${this.page}`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.bookmarks = response.data.data
            this.lastPage = response.data.last_page
        },
        getPage () {
            this.page = Number(this.$route.query.page)
            if (! this.page) {
                this.page = 1
            }
        },
        next ({page}) {
            this.$router.push(`/users/${this.id}/bookmark/?page=${page}`)
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
                this.getPage()
                await this.fetchBookmarks()
            },
            immediate: true
        }
    }
}
</script>