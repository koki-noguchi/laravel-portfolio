<?php

namespace App\Http\Controllers;

use App\Post;
use App\Services\PostListInterface;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    /**
     * ブックマーク
     * @param Post $post
     * @return array
     */
    public function bookmark(Post $post)
    {
        $post->load('bookmarks');

        $post->bookmarks()->detach(Auth::user()->id);
        $post->bookmarks()->attach(Auth::user()->id);

        return ["post_id" => (int) $post->id];
    }

    /**
     * ブックマークを外す
     * @param Post $post
     * @return array
     */
    public function deleteBookmark(Post $post)
    {
        $post->load('bookmarks');

        $post->bookmarks()->detach(Auth::user()->id);

        return ["post_id" => (int) $post->id];
    }

    /**
     * ログイン中ユーザーのブックマークを取得
     * @return array
     */
    public function bookmarkList(PostListInterface $postList)
    {
        $posts = $postList->BookmarkList(Auth::user()->bookmark_post->pluck('id'));

        return $posts;
    }
}
