<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Post;
use App\Http\Requests\StorePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * 画像の削除
     * @param Photo $photo
     * @return Photo
     */
    public function delete(Photo $photo)
    {
        $post_user = $photo->postBy->user_id;

        if ((int) $post_user !== Auth::user()->id) {
            abort(401);
            return;
        }

        if (Storage::cloud()->exists($photo->post_photo)) {
            Storage::cloud()->delete($photo->post_photo);
            $photo->delete();
        } else {
            abort(404);
        }
    }

    /**
     * 画像追加
     * @param Post $post
     * @param StorePhoto $request
     * @return \Illuminate\Http\Response
     */
    public function create(Post $post, StorePhoto $request)
    {
        $images = $request->post_photo;

        if ((int) $post->user_id !== Auth::user()->id) {
            abort(401);
            return;
        }

        if ($images) {
            foreach ($images as $image ) {
                $photo = new Photo();
                $extension = $image->extension();

                $photo->post_photo = $photo->id . '.' . $extension;

                Storage::cloud()->putFileAs('post_photo', $image, $photo->post_photo, 'public');
                $photo->post_photo = 'post_photo/' . $photo->id . '.' . $extension;

                DB::beginTransaction();

                try {
                    $photo->post_id = $post->id;
                    $photo->save();
                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollBack();

                    Storage::cloud()->delete($photo->post_photo);
                    throw $exception;
                }
            }

            return response($photo, 201);
        }
    }
}
