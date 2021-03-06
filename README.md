# MessageShare
MessageShareはメッセージを募集して、完成したページを仲間と共有するWebアプリケーションです。

## URL：https://www.messageshare.work
![スクリーンショット 2020-09-02 18-41-55](https://user-images.githubusercontent.com/67818050/91966136-6968e180-ed4c-11ea-9358-e868f8403c8e.png)

## ポートフォリオの見どころ
### インフラ
    * CircleCIでCI/CDパイプラインを構築している
    * AWSを使用し、ALBを通して常時SSL通信を行っている

### バックエンド
    * Laravelのサービスコンテナを使い、ファットコントローラ対策をしている
    * APIを作成するときに同時にテストを書いている

### フロントエンド
    * Vue、Vuex、Vue Routerを使ってSPA（シングルページアプリケーション）に対応している
    * CSSフレームワークのVuetifyを採用し、レスポンシブデザインに対応している

## サービス構成図
![AWS構成図](https://user-images.githubusercontent.com/67818050/92076440-84dbf700-edf5-11ea-99c6-d783ae316fa4.png)

## 機能一覧
### 認証
* ユーザー登録・編集・削除
    * ID、パスワード、ユーザー名必須
    * ゲストログイン機能（退会・プロフィール変更不可）
    * Vuexを使用したログイン情報の管理

### 投稿機能
* 投稿・編集・削除
* 記事へのコメント投稿・削除・数を制限
* 記事へのコメントへのコメント投稿・削除
* ブックマーク

### ユーザーフォロー機能
* フォロー・フォロワー一覧を表示
* フォロー・フォロー解除
* タイムラインにフォロー中のユーザーの投稿を表示

### ページネーション
* 募集ページと募集履歴には無限スクロール（Vue-infinite-loading）を使用

### 画像アップロード機能
* AWSのS3へ保存
* プロフィール画像をトリミングしてアップロード（Vue Croppaを使用）

### 投稿検索
* 記事IDまたはフリーワードで検索可能

## 使用技術一覧
* PHP7.4
* Laravel7.19.0
* Vue.js2.5.17
* Vue Router3.3.4
* Vuex3.5.1
* Vue Croppa1.3.8
* Vue-infinite-loading2.4.5
* vuetify2.3.6
* MySQL5.7
* Docker/docker-compose(開発環境)
* CircleCI
* AWS(VPC,RDS,EC2,S3,ALB,Route53,ACM)
