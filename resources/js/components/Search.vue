<template>
    <div class="post-search">
        <form class="form" @submit.prevent="search">
            <div class="form__item">
                <input
                  class="input--keyword"
                  type="text"
                  v-model="keyword"
                  placeholder="id or title">
            </div>
            <button class="btn button--search"><span></span></button>
        </form>
    </div>
</template>

<script>
export default {
    data () {
        return {
            keyword: ''
        }
    },
    methods: {
        async search () {
            if (keyword === '') {
                return false
            }
            const response = await axios.get('/api/post', {
                keyword: this.keyword,
            })
            this.keyword = ''
            this.$router.push('/post')
        }
    }
}
</script>