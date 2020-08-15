<template>
    <v-row>
        <ServiceDescription />
        <v-col cols="12">
            <div class="h2 text-center my-12">MessageShareで何ができる？</div>
            <v-card
                v-for="item in items"
                :key="item.title">
                <div class="d-flex flex-no-wrap mx-auto" style="max-width:1000px;">
                    <div class="text-center px-12 py-5" style="width:200px;">
                        <div class="text-center">
                            <v-icon class="pt-8" color="red lighten-2" size="70">{{ item.icon }}</v-icon>
                        </div>
                            <div
                                style="white-space:pre-wrap;"
                                class="text-center red--text lighten-2 subtitle-1"
                                v-text="item.title"
                            ></div>
                    </div>
                    <v-card-subtitle
                        class="body-2 my-auto pr-10"
                        style="white-space:pre-wrap;"
                        v-text="item.description"
                    ></v-card-subtitle>
                </div>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import ServiceDescription from '../components/ServiceDescription'

export default {
    components: {
        ServiceDescription
    },
    data () {
        return {
            items: [
                { icon: 'note_add', title: `メッセージを募集する`,
                description:
                `募集を開始すると、そのページを見た人からメッセージを受け取れます。\n例えば、仲間と一緒におめでとう文を送り、友達の誕生日にリンクを共有すれば喜んでもらえるでしょう。\n\n画像を投稿して思い出を共有することもできます。`
                },
                { icon: 'filter_1', title: `募集人数を設定できる`,
                description:
                `受け取れるメッセージの数に制限を加えられます。\n指定人数を超えたメッセージは受け取れないので注意してください。\n\nメッセージの送信回数は一人一回までです。様々な人からの投稿を受け取りましょう。`
                },
                { icon: 'message', title: `リプライ機能`,
                description:
                    `ログイン中のユーザーなら誰でも、メッセージに返信ができます。\nもしあなたが質問を投げたのなら、募集者から回答が来るかもしれません。\n\n返信が届くと、メッセージの下に数字が表示されます。`
                },
                { icon: 'bookmark', title: `お気に入りの募集ページを\nすぐに見返せる`,
                description:
                    `お気に入りのページをブックマークすると、マイページにリンクが追加されます。\nページを何度も見返したり、メッセージを後から送ったりしたいときにこの機能を役立てましょう。\n\n気になるユーザーをフォローして投稿履歴を追うこともできます。`
                }
            ]
        }
    }
}
</script>