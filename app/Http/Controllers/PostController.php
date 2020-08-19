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
        $post->about = $request->get('about');
        $post->user_id = Auth::user()->id;
        $post->save();

        $images = $request->post_photo;

        if ($images) {
            foreach ($images as $image ) {
                $photo = new Photo();
                $extension = $image->extension();

                $photo->post_photo = $photo->id . '.' . $extension;

                Storage::cloud()->putFileAs('post_photo', $image, $photo->post_photo, 'public');
                $photo->post_photo = 'post_photo/' . $photo->id . '.' . $extension;

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
                ->with(['user', 'user.followings', 'user.followers', 'bookmarks', 'photos', 'messages'])->orderBy('created_at', 'desc')->paginate();
        } else {
            $posts = Post::with(['user', 'user.followings', 'user.followers', 'bookmarks', 'photos', 'messages'])->orderBy('created_at', 'desc')->paginate();
        }

        $posts->makeHidden(['messages']);
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
     * フォロー中のユーザーの募集リストを取得
     * @return array
     */
    public function timeline()
    {
        $post = Post::query()
            ->whereIn('user_id', Auth::user()->followings->pluck('id'))
            ->with(
                'user', 'messages', 'bookmarks',
                'photos', 'user.followings', 'user.followers'
            )
                ->orderBy('created_at', 'desc')->paginate();
        return $post;
    }

    /**
     * メッセージ募集の削除
     * @param Post $post
     * @return Post
     */
    public function delete(Post $post)
    {
        $post->load('photos');

        if ((int) $post->user_id !== Auth::user()->id) {
            abort(401);
            return;
        }

        foreach ($post->photos as $photo) {
            if (Storage::cloud()->exists($photo->post_photo)) {
                Storage::cloud()->delete($photo->post_photo);
            }
        }

        $post->delete();
    }

    /**
     * メッセージ募集の更新
     * @param Post $post
     * @param UpdatePost $request
     * @return \Illuminate\Http\Response
     */
    public function update(Post $post, UpdatePost $request)
    {
        if ((int) $post->user_id !== Auth::user()->id) {
            abort(401);
            return;
        }

        $post->fill($request->all())->save();

        $new_post = Post::where('id', $post->id)->first();
        return $new_post;
    }

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
}