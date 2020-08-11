<template>
    <div>
        <v-alert
            v-show="successContent"
            type="success"
            max-height="40"
            dense>
            {{ successContent }}
        </v-alert>

        <v-alert
            v-show="errorContent"
            type="error"
            max-height="40"
            dense>
            {{ errorContent }}
        </v-alert>
    </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
    computed: {
        ...mapState({
            successContent: state => state.message.successContent,
            errorContent: state => state.message.errorContent
        })
    }
}
</script>