<template>
    <div>
        <p>EDIT USER</p>
        <p>login_id</p>
        <input 
          type="text"
          v-model="login_id">
        <p>name</p>
        <input
          type="text"
          v-model="name">
        <ProfileImage :imgSrc="user_image"></ProfileImage>
        <div class="form__button">
            <button
                class="button button__inverse"
                @click.prevent="update"
            >送信</button>
        </div>
        <div class="form__button">
            <button
                @click.prevent="deleteUser"
            >退会する</button>
        </div>
    </div>
</template>

<script>
import { OK } from "../util"
import ProfileImage from '../components/ProfileImage.vue'

export default {
    components: {
        ProfileImage
    },
    data () {
        return {
            login_id: '',
            name: '',
            user_image: '',
        }
    },
    methods: {
        async fetchUser () {
            const response = await axios.get('/api/myuser')

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.login_id = response.data.login_id
            this.name = response.data.name
            this.user_image = response.data.user_image
        },
        async update () {
            const response = await axios.put('/api/user', {
                name: this.name,
                login_id: this.login_id,
            })

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }
            this.name = ''
            this.login_id = ''

            await this.fetchUser()
        },
        async deleteUser () {
            const response = await axios.delete('/api/user')

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }
            this.$store.commit('auth/setUser', null)
            this.$router.push('/')
        }
    },
    watch: {
        $route: {
            async handler () {
                await this.fetchUser()
            },
            immediate: true
        }
    }
}
</script>