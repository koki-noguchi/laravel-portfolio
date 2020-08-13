<template>
    <div class="post-search">
        <v-form
            @submit.prevent="search"
            ref="form"
            v-model="valid"
            lazy-validation>
            <v-text-field
                @keydown.enter.prevent="search"
                v-model="keyword"
                prepend-inner-icon="search"
                placeholder="id or title"
                clearable
                dense
                class="mt-3 shrink"
                style="width: 260px;"
                :rules="[rules.required]"
                >
            </v-text-field>
            <v-btn type="submit">検索</v-btn>
        </v-form>
    </div>
</template>

<script>
export default {
    data () {
        return {
            keyword: '',
            valid: true,
            rules: {
                required: value => !!value || 'キーワードを入力してください。'
            }
        }
    },
    methods: {
        search () {
            if (!this.$refs.form.validate()) {
                return false
            }
            let params = "&keyword=" + this.keyword
            this.$emit('search', params)
        }
    }
}
</script>