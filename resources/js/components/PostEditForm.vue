<template>
    <v-row justify="center">
        <v-col
        cols="12"
        sm="8"
        md="6"
        >
        <form
            class="mt-10 text-center"
            @submit.prevent="update"
        >
            <v-text-field
            class="mt-5"
            v-model="post_title"
            filled
            counter
            maxlength="100"
            clearable
            label="タイトル"
            >
            </v-text-field>
            <v-textarea
            class="mt-5"
            v-model="about"
            filled=""
            auto-grow
            counter
            maxlength="2000"
            clearable
            label="概要"
            >
            </v-textarea>
            <v-btn
            type="submit"
            class="mt-5"
            width="160"
            outlined color="pink lighten-1"
            >送信</v-btn>
        </form>
        <div class="text-right">
        <v-btn
            dark
            fab
            bottom
            right
            color="red"
            @click.stop="dialog = true"
            class="mt-12"
        >
            <v-icon>delete_sweep</v-icon>
        </v-btn>
        </div>
        <v-dialog
            v-model="dialog"
            max-width="400"
        >
            <v-card>
            <v-card-title class="headline">メッセージ募集ページを削除してもよろしいですか？</v-card-title>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                color="green darken-1"
                text
                @click="dialog = false"
                @click.prevent="deletePost"
                >
                Yes
                </v-btn>

                <v-btn
                color="red darken-1"
                text
                @click="dialog = false"
                >
                No
                </v-btn>
            </v-card-actions>
            </v-card>
        </v-dialog>
        </v-col>
    </v-row>
</template>

<script>
import { OK } from '../util'

export default {
    props: {
        id: {
            type: String,
            required: true
        }
    },
    data () {
        return {
            post: null,
            post_title: '',
            about: '',
            dialog: false,
        }
    },
    methods: {
        async fetchPost () {
            const response = await axios.get(`/api/post/${this.id}`)

            if (!response.data.my_post) {
                this.$router.push(`/post/${this.id}`)
            }

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.post = response.data
            this.post_title = this.post.post_title
            this.about = this.post.about
        },
        async update () {
            const response = await axios.put(`/api/post/${this.id}`,
            {
                post_title: this.post_title,
                about: this.about
            })
            await this.fetchPost()
        },
        async deletePost () {
            const response = await axios.delete(`/api/post/${this.id}`)

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.$router.push('/post')
        },
    },
    watch: {
        $route: {
            async handler () {
                await this.fetchPost()
            },
            immediate: true
        },
    }
}
</script>