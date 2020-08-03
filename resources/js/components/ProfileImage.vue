<template>
    <v-form @submit.prevent="setUserPhoto">
        <v-list>
            <v-list-item class="justify-center">
                <croppa
                    v-model="myCroppa"
                    :width="150"
                    :height="150"
                    placeholder="プロフィール画像をアップロード"
                    :placeholder-font-size="0"
                    :disabled="false"
                    :prevent-white-space="true"
                    :show-remove-button="true"
                    :accept="'image/*'"
                    :file-size-limit="2 * 1024 * 1024"
                    @file-size-exceed="onFileSizeExceed"
                    @file-type-mismatch="onFileTypeMismatch"
                ></croppa>
            </v-list-item>
            <v-list-item class="justify-center mt-2">
                <v-btn type="submit">変更する</v-btn>
            </v-list-item>
        </v-list>
    </v-form>
</template>

<script>
import "vue-croppa/dist/vue-croppa.css"
import { OK } from "../util"

export default {
    props: {
        imgSrc: String
    },
    data () {
        return {
            myCroppa: {},
            generateImg: ''
        }
    },
    methods: {
        async setUserPhoto () {
            this.generateImg = this.myCroppa.generateDataUrl()

            const response = await axios.put('/api/user', {
                user_image: this.generateImg
            })

            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }
            this.generateImg = ''

            this.$router.push('/mypage').catch(()=>{})
        },
        onFileTypeMismatch () {
            Swal.fire({
                type: "error",
                title: "対応していないファイル形式です",
                text: "画像ファイルを選択してください。"
            });
        },
        onFileSizeExceed () {
            Swal.fire({
                type: "error",
                title: "画像のデータサイズが大きすぎます",
                text: "プロフィールに使える画像のデータサイズは2MBまでです。"
            });
        }
    }
}
</script>