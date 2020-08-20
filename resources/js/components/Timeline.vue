<template>
    <div>
        <Post
            v-for="timeline in timelines"
            :key="timeline.id"
            :item="timeline"
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
            timelines: null,
        }
    },
    methods: {
        async fetchTimeline () {
            const response = await axios.get(`/api/users/timeline`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.timelines = response.data.data
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

            this.timelines = this.timelines.map(timeline => {
                if (timeline.id === response.data.post_id) {
                    timeline.bookmarked_by_user = true
                }

                return timeline
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

            this.timelines = this.timelines.map(timeline => {
                if (timeline.id === response.data.post_id) {
                    timeline.bookmarked_by_user = false
                }

                    return timeline
                })
            this.$store.commit('message/setSuccessContent', {
                successContent: 'ブックマークを外しました。',
            })
        },
    },
    watch: {
        $route: {
            async handler () {
                await this.fetchTimeline()
            },
            immediate: true
        }
    }
}
</script>