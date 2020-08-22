<?php

namespace App\Components;

use App\Post;
use App\Services\PostListInterface;

class PostList implements PostListInterface
{
    public function Search($keyword)
    {
        $posts = new Post();
        $result = $posts->when(!is_null($keyword), function ($query) use ($keyword) {
            return $query->where('posts.id', 'like', '%'.$keyword.'%')
                ->orWhere('posts.post_title', 'like', '%'.$keyword.'%');
        })->when(is_null($keyword), function ($query) {
            return $query;
        })
            ->with(['user', 'user.followings', 'user.followers',
                    'bookmarks', 'photos', 'messages'
            ])->orderBy('created_at', 'desc')->paginate();

        $result->makeHidden(['messages']);

        return $result;
    }

    public function List($user_id)
    {
        $posts = new Post();

        $result = $posts->whereIn('posts.user_id', $user_id)
            ->with([
                'user', 'user.followings', 'user.followers',
                'bookmarks', 'photos', 'messages'
            ])->orderBy('created_at', 'desc')->paginate();

        $result->makeHidden(['messages']);
        return $result;
    }

    public function BookmarkList($post_id)
    {
        $posts = new Post();

        $result = $posts->whereIn('posts.id', $post_id)
            ->with([
                'user', 'user.followings', 'user.followers',
                'bookmarks', 'photos', 'messages'
            ])->orderBy('created_at', 'desc')->paginate();

        $result->makeHidden(['messages']);
        return $result;
    }
}