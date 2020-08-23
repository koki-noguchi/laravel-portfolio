<?php

namespace App\Components;

use App\Post;
use App\Services\PostSaveInterface;
use Illuminate\Support\Facades\Auth;

class PostSave implements PostSaveInterface
{
    public function Create($title, $password, $max_number, $about)
    {
        $post = new Post();
        $post->post_title = $title;
        $post->post_password = $password;
        $post->max_number = $max_number;
        $post->about = $about;
        $post->user_id = Auth::user()->id;
        $post->save();

        return $post;
    }

    public function Update($post, $params)
    {
        $post->fill($params->all())->save();

        $new_post = Post::where('id', $post->id)->first();
        return $new_post;
    }
}