<template>
    <div>
        <User
            :user="user"
            @updateUser="updateUser"
            @setUserPhoto="setUserPhoto"
        ></User>
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
    props: {
        id: {
            type: String,
            required: true
        }
    },
    data () {
        return {
            posts: null,
            user: {
                login_id: '',
                name: '',
                url: ''
            },
            my_bookmark_post: '',
            histories: null,
            bookmarks: null
        }
    },
    methods: {
        async fetchProfile () {
            const response = await axios.get(`/api/users/${this.id}`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.posts = response.data
            this.user.login_id = response.data.login_id
            this.user.name = response.data.name
            this.user.url = response.data.url
            this.histories = response.data.posts
            this.bookmarks = response.data.bookmark_post
        },
        async updateUser ({ login_id, name }) {
            const response = await axios.put('/api/user', {
                login_id: login_id,
                name: name,
            })

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }
        },
        async setUserPhoto ({ user_image }) {
            const response = await axios.put('/api/user', {
                user_image: user_image
            })

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.user.url = response.data.url
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
                    this.bookmarks.push(history)
                }

                return history
            })
        },
        async deleteBookmark (id) {
            const response = await axios.delete(`/api/post/${id}/bookmark`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.bookmarks = this.bookmarks.map((bookmark, i) => {
                if (bookmark.id === response.data.post_id) {
                    bookmark.bookmarked_by_user = false
                    this.fetchProfile()
                }
                return bookmark
            })
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