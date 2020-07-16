<template>
    <div v-if="post" class="post-detail">
        <h1 class="post-detail__title">{{ post.post_title }}</h1>
        <div class="post-detail__about">
            {{ post.about }}
        </div>
        <div class="post-detail__name">
            {{ post.user.name}}
        </div>
        <button @click.prevent="deletePost">募集ページの削除</button>
        <button >edit</button>
        <PostModal
          :post="post"
          @update="updatePost"
        ></PostModal>
    </div>
</template>

<script>
import { OK } from '../util'
import PostModal from '../components/PostModal.vue'
export default {
    components: {
        PostModal
    },
    props: {
        id: {
            type: String,
            required: true
        }
    },
    data () {
        return {
            post: null,
        }
    },
    methods: {
        async fetchPost () {
            const response = await axios.get(`/api/post/${this.id}`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.post = response.data
        },
        async deletePost () {
            const response = await axios.delete(`/api/post/${this.id}`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.$router.push('/post')
        },
        updatePost ({ post_title, about }) {
            this.update(post_title, about)
        },
        async update (post_title, about) {
            const response = await axios.put(`/api/post/${this.id}`,
            {
                post_title: post_title,
                about: about
            })
            await this.fetchPost()
        }
    },
    watch: {
        $route: {
            async handler () {
                await this.fetchPost()
            },
            immediate: true
        }
    }
}
</script>