<template>
    <div>
        <v-row justify="center">
            <v-col
                cols="12"
                sm="8"
                md="6"
            >
            <form
                class="mt-10 text-center"
                @submit.prevent="create"
            >
                <v-file-input
                    class="mt-5"
                    :rules="rules"
                    accept="image/*"
                    label="画像の追加"
                    prepend-icon="photo"
                    multiple
                    v-model="files"
                    @change="onFileChange"
                    show-size
                    counter
                >
                <template v-slot:selection="{ text }">
                    <v-chip
                    small
                    label
                    color="primary"
                    close
                    @click:close="remove(index)"
                    >
                    {{ text }}
                    </v-chip>
                </template>
                </v-file-input>
                <v-row>
                <v-col sm="6" v-for="(file,f) in files" :key="f">
                    {{file.name}}
                    <img
                        :ref="'file'"
                        src=""
                        class="img-fluid"
                        :title="'file' + f"
                    />
                </v-col>
                </v-row>
                <v-btn
                type="submit"
                width="160"
                class="ma-2 mt-10"
                outlined color="pink lighten-1"
                >送信</v-btn>
            </form>
            </v-col>
        </v-row>
        <v-row v-if="photos_list.length > 0">
            <v-col
            sm="4"
            v-for="photo in photos_list"
            :key="photo.photos_url"
            >
                <v-card>
                    <v-img
                    :src="photo.photos_url"
                    >
                    </v-img>
                    <v-card-actions>
                    <v-btn
                        color="orange"
                        text
                        @click.prevent="photoDelete(photo.id)"
                    >
                    Delete
                    </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import { OK } from '../util'

export default {
    props: {
        photos_list: {
            type: Array,
            required: true
        },
        id: {
            type: String,
            required: true
        }
    },
    data () {
        return {
        photos: [],
        post_photo: [],
        files: [],
            readers: [],
            index: '',
            rules: [
            value => !value.length || value.reduce((size, file) => size + file.size, 0) < 10240000 || 'サイズを10MB以内に抑えてください。',
        ]
        }
    },
    methods: {
        async create () {
            const formData = new FormData()

            if (this.files.length > 0) {
                for (let index = 0; index < this.files.length; index++) {
                formData.append('post_photo[]', this.post_photo[index])
                }
            }

            const response = await axios.post(`/api/post/${this.id}/photo`, formData)
            this.post_photo = ''

            this.$router.push(`/post/${this.id}`)
        },
        remove (index) {
            this.files.splice(index, 1)
        },
        onFileChange () {
            this.files.forEach((file, f) => {
                this.readers[f] = new FileReader();
                this.readers[f].onloadend = (e) => {
                    let fileData = this.readers[f].result
                    let imgRef = this.$refs.file[f]
                    imgRef.src = fileData
                }

                this.readers[f].readAsDataURL(this.files[f]);
                this.post_photo[f] = this.files[f]
            })
        },
        photoDelete (photo_id) {
            this.$emit('photoDelete', {
                photo_id: photo_id
            })
        }
    },
}
</script>