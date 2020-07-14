<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * メッセージ募集
     * @param StorePhoto $request
     * @return \Illuminate\Http\Response
     */
    public function create(StorePost $request)
    {
        $post = new Post();
        $post->post_title = $request->get('post_title');
        if ($request->get('post_password') !== null){
            $post->post_password = Hash::make($request->get('post_password'));
        } else {
            $post->post_password = ($request->get('post_password'));
        }
        $post->min_number = $request->get('min_number');
        $post->max_number = $request->get('max_number');
        $post->share_judge = 0;
        $post->about = '';
        $post->user_id = Auth::user()->id;
        $post->save();

        return response($post, 201);
    }

    /**
     * メッセージ募集一覧
     */
    public function index()
    {
        $posts = Post::with(['user'])->orderBy('created_at', 'desc')->paginate();

        return $posts;
    }
}
