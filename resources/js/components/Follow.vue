<template>
    <v-card class="text-decoration-none my-5 mx-auto" max-width="600">
        <v-row class="text-body-2 pt-2">
            <v-col>
                <v-card-actions class="justify-center ml-7">
                    <router-link :to="`/users/${item.id}`">
                        <v-list-item-avatar size="30">
                            <v-img :src="item.url"></v-img>
                        </v-list-item-avatar>
                    </router-link>
                    <v-list-item-title
                        class="text-body-2 text-wrap"
                    >
                        {{ item.name }}
                    </v-list-item-title>
                </v-card-actions>
            </v-col>
            <v-spacer></v-spacer>
            <v-col>
                <v-card-actions class="justify-center">
                    <v-btn
                        v-if="myUserId !== item.id"
                        class="white--text text-decoration-none"
                        :color="item.followed_judge === true ? 'grey' : 'blue'"
                        @click="followBtnClick"
                    >フォロー</v-btn>
                </v-card-actions>
            </v-col>
        </v-row>
    </v-card>
</template>

<script>
export default {
    props: {
        item: {
            type: Object,
            required: true
        },
    },
    methods: {
        followBtnClick () {
            this.$emit('followBtnClick', {
                id: this.item.id,
                followed_judge: this.item.followed_judge
            })
        }
    },
    computed: {
        myUserId () {
            return this.$store.getters['auth/id']
        }
    }
}
</script>