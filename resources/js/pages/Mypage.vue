<template>
    <div>
        <User></User>
        <v-tabs
            centered
            class="mt-5"
        >
            <v-tab href="#history">
            History
            </v-tab>
            <v-tab-item id="history">
            <History
                v-for="history in histories"
                :key="history.id"
                :item="history"
            ></History>
            </v-tab-item>
            <v-tab href="#bookmark">
            Bookmark
            </v-tab>
            <v-tab-item id="bookmark">
            </v-tab-item>
        </v-tabs>
    </div>
</template>

<script>
import { OK } from '../util'
import History from '../components/Post.vue'
import User from '../components/User.vue'

export default {
    components: {
        History,
        User
    },
    data () {
        return {
            histories: null,
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
        }
    },
    watch: {
        $route: {
            async handler () {
                await this.fetchHistory()
            },
            immediate: true
        }
    }
}
</script>