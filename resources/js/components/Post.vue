<template>
  <div class="post">
    <v-card :to="`/post/${item.id}`" class="text-decoration-none my-10">
      <div class="text-right text-body-2 mr-3 pt-2">{{ item.updated_at }}</div>
      <div class="d-flex flex-no-wrap justify-space-between">
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
      </div>
    </v-card>
  </div>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true
    }
  },
}
</script>