<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Http\Requests\UpdatePost;
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
        $this->middleware('auth')->except('index', 'show');
    }

    /**
     * メッセージ募集
     * @param StorePost $request
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
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        if ($request->has('keyword')) {
            $posts = Post::where('id', 'like', '%'.$keyword.'%')->orWhere('post_title', 'like', '%'.$keyword.'%')
                ->with(['user'])->orderBy('created_at', 'desc')->paginate();
        } else {
            $posts = Post::with(['user'])->orderBy('created_at', 'desc')->paginate();
        }
        return $posts;
    }

    /**
     * メッセージ募集の詳細
     * @params string $id
     * @return Post
     */
    public function show(string $id)
    {
        $post = Post::where('id', $id)->with(['user', 'messages.author'])->first();

        return $post ?? abort(404);
    }

    /**
     * メッセージ募集の削除
     * @params string $id
     * @return Post
     */
    public function delete(string $id)
    {
        $post = Post::where('id', $id)->first();

        if (! $post) {
            abort(404);
        }

        $post->delete();
    }

    /**
     * メッセージ募集の更新
     * @params string $id
     * @param UpdatePost $request
     * @return \Illuminate\Http\Response
     */
    public function update(string $id, UpdatePost $request)
    {
        $post = Post::find($id);
        $post->fill($request->all())->save();

        $new_post = Post::where('id', $post->id)->first();
        return $new_post;
    }
}