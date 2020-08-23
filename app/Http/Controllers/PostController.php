<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Http\Requests\UpdatePost;
use App\Post;
use App\User;
use App\Services\AuthorizationInterface;
use App\Services\PhotoInterface;
use App\Services\PostListInterface;
use App\Services\PostSaveInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show', 'history');
    }

    /**
     * メッセージ募集一覧
     */
    public function index(Request $request, PostListInterface $postSearch)
    {
        $posts = $postSearch->Search($request->input('keyword'));
        return $posts;
    }

    /**
     * タイムラインを取得
     * @return array
     */
    public function timeline(PostListInterface $postList)
    {
        $posts = $postList->List(Auth::user()->followings->pluck('id'));
        return $posts;
    }

    /**
     * ユーザーのメッセージ募集履歴を取得
     * @param User $user
     * @return Post
     */
    public function history(User $user, PostListInterface $postList)
    {
        $posts = $postList->List([$user->id]);
        return $posts;
    }

    /**
     * メッセージ募集の詳細
     * @param Post $post
     * @return Post
     */
    public function show(Post $post)
    {
        $post->load([
            'user', 'messages.author.followings',
            'messages.author.followers', 'bookmarks', 'photos',
            'messages.replies'])->first();
        $post->makeVisible(['messages']);
        return $post;
    }

    /**
     * メッセージ募集
     * @param StorePost $request
     * @return \Illuminate\Http\Response
     */
    public function create(StorePost $request, PostSaveInterface $postSave, PhotoInterface $photoCreate)
    {
        $post = $postSave->Create(
            $request->get('post_title'), $request->passwordHash(),
            $request->get('max_number'), $request->get('about')
        );

        $images = $request->post_photo;

        if ($images) {
            foreach ($images as $image ) {
                $photo = $photoCreate->NewFileName($image);
                $photoCreate->PhotoSave($post, $photo, "post_photo", $photo->post_photo, $image);
            }
        }

        return response($post, 201);
    }

    /**
     * メッセージ募集の更新
     * @param Post $post
     * @param UpdatePost $request
     * @return \Illuminate\Http\Response
     */
    public function update(Post $post, UpdatePost $request, AuthorizationInterface $authorization, PostSaveInterface $postSave)
    {
        $authorization->Check((int) $post->user_id);

        $new_post = $postSave->Update($post ,$request);

        return $new_post;
    }

    /**
     * メッセージ募集の削除
     * @param Post $post
     * @return Post
     */
    public function delete(Post $post, AuthorizationInterface $authorization, PhotoInterface $photoDelete)
    {
        $post->load('photos');
        $authorization->Check((int) $post->user_id);

        $photoDelete->Delete($post->photos);
        $post->delete();
    }
}