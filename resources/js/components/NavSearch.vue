<template>
    <v-form
        ref="form"
        v-model="valid"
        lazy-validation
    >
        <v-text-field
            @keydown.enter.prevent="search"
            v-model="keyword"
            clearable
            flat
            label="Search"
            prepend-inner-icon="search"
            solo
            single-line
            hide-details
            :rules="[rules.required]"
        ></v-text-field>
    </v-form>
</template>

<script>
export default {
    data () {
        return {
            keyword: '',
            valid: true,
            rules: {
                required: value => !!value
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