<template>
  <div class="post">
    <v-card class="text-decoration-none my-10" @click="goToPostLink">
      <v-row class="text-body-2 pt-2">
        <v-col>
          <v-card-text class="ml-3 justify-center">
            {{ item.updated_at }}
          </v-card-text>
        </v-col>
        <v-spacer></v-spacer>
        <v-col>
          <v-card-actions class="bookmark justify-center">
            <v-btn
              v-if="isLogin"
              class="white--text text-decoration-none"
              :color="item.bookmarked_by_user === true ? 'grey' : 'orange'"
              @click.stop="bookmark(item.id, item.bookmarked_by_user)"
            >bookmark
              <v-icon color="white">bookmark</v-icon>
            </v-btn>
          </v-card-actions>
        </v-col>
      </v-row>
      <v-list class="d-flex flex-no-wrap justify-space-between">
        <div>
          <v-card-title class="headline ml-2">{{ item.post_title }}</v-card-title>
          <v-row>
            <v-col
              cols="2"
              sm="2"
              md="3"
              class="text-truncate">
              <v-card-subtitle class="ml-6">{{ item.about }}</v-card-subtitle>
            </v-col>
          </v-row>
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
        </div>
        <v-avatar
          class="ma-3"
          size="150"
          tile
          v-if="item.photos.length > 0"
        >
          <v-img :src="item.photos[0].photos_url"></v-img>
        </v-avatar>
      </v-list>
    </v-card>
  </div>
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