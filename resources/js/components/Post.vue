<template>
      <v-card
        class="text-decoration-none my-10 mx-auto"
        max-width="600"
        @click="goToPostLink">
        <v-row class="text-body-2 pt-0">
          <v-col>
            <v-card-text class="justify-center py-0">
              {{ item.updated_at }}
            </v-card-text>
          </v-col>
          <v-col class="pb-0">
            <v-card-actions class="text-right mr-3 pa-0">
              <v-spacer></v-spacer>
              <v-btn
                v-if="isLogin"
                class="white--text text-decoration-none"
                :color="item.bookmarked_by_user === true ? 'grey' : 'orange'"
                @click.stop="bookmark(item.id, item.bookmarked_by_user)"
              >
                <v-icon color="white">bookmark</v-icon>
              </v-btn>
            </v-card-actions>
          </v-col>
        </v-row>
        <v-card-title class="headline ml-3 mt-1 pa-0">{{ item.post_title }}</v-card-title>
        <v-card-subtitle class="ml-3 my-3 text-truncate pa-0">{{ item.about }}</v-card-subtitle>
        <v-img
          v-if="item.photos.length > 0"
          height="300"
          :src="item.photos[0].photos_url"
        ></v-img>
        <v-card-actions>
          <v-list-item>
            <v-list-item-avatar size="30px">
                <v-img
                    :src="item.user.url"
                ></v-img>
            </v-list-item-avatar>
            <v-list-item-content>
                <v-list-item-title
                    class="text-body-2 text-wrap">
                    {{ item.user.name }}
                </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-card-actions>
      </v-card>
</template>

<script>
import { OK } from '../util'

export default {
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  methods: {
    bookmark (id, bookmarked_by_user) {
      this.$emit('bookmark', {
        id: id,
        bookmarked_by_user: bookmarked_by_user
      })
    },
    goToPostLink (e) {
      if (e.target.classList.contains("bookmark")) {
        e.stopPropagation()
      } else {
        this.$router.push(`/post/${this.item.id}`)
      }
    }
  },
  computed: {
    isLogin () {
        return this.$store.getters['auth/check']
    },
  }
}
</script>