<template>
    <v-card>
        <v-card-text
            class="text-body-1 black--text"
        >
            {{ message.message_text }}
        </v-card-text>
        <v-card-actions>
            <v-list-item>
                <v-list-item-avatar size="30px">
                    <v-img
                        :src="message.author.url"
                    ></v-img>
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title
                        class="text-body-2 text-wrap">
                        {{ message.author.name }}
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    props: {
        message: {
            required: true
        }
    }
}
</script>