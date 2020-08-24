<template>
    <div class="text-center">
        <v-pagination
            v-model="currentPage"
            :length="lastPage"
            :total-visible="5"
            @input="next"
        ></v-pagination>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            type: String,
            required: true
        },
        page: {
            type: Number,
            required: true
        },
        lastPage: {
            type: Number,
            required: true
        },
    },
    data () {
        return {
            currentPage: 1
        }
    },
    methods: {
        next () {
            this.$emit('next', {
                page: this.currentPage
            })
        }
    },
    mounted() {
        this.currentPage = this.page
    }
}
</script>