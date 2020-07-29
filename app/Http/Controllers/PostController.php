<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Http\Requests\UpdatePost;
use App\Post;
use App\User;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

        $post->max_number = $request->get('max_number');
        $post->share_judge = 0;
        $post->about = '';
        $post->user_id = Auth::user()->id;
        $post->save();

        $images = $request->post_photo;

        if ($images) {
            foreach ($images as $image ) {
                $photo = new Photo();
                $extension = $image->extension();

                $photo->post_photo = 'post_photo/' . $photo->id . '.' . $extension;

                Storage::cloud()->put($photo->post_photo, $image, 'public');

                DB::beginTransaction();

                try {
                    $post->photos()->save($photo);
                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollBack();

                    Storage::cloud()->delete($photo->post_photo);
                    throw $exception;
                }
            }
        }

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
                ->with(['user', 'bookmarks'])->orderBy('created_at', 'desc')->paginate();
        } else {
            $posts = Post::with(['user', 'bookmarks'])->orderBy('created_at', 'desc')->paginate();
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
        $post = Post::where('id', $id)->with(['user', 'messages.author', 'bookmarks'])->first();

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

    /**
     * ブックマーク
     * @param string $id
     * @return array
     */
    public function bookmark(string $id)
    {
        $post = Post::where('id', $id)->with('bookmarks')->first();

        if (! $post) {
            abort(404);
        }

        $post->bookmarks()->detach(Auth::user()->id);
        $post->bookmarks()->attach(Auth::user()->id);

        return ["post_id" => (int) $id];
    }

    /**
     * ブックマークを外す
     * @param string $id
     * @return array
     */
    public function deleteBookmark(string $id)
    {
        $post = Post::where('id', $id)->with('bookmarks')->first();

        if (! $post) {
            abort(404);
        }

        $post->bookmarks()->detach(Auth::user()->id);

        return ["post_id" => (int) $id];
    }
}