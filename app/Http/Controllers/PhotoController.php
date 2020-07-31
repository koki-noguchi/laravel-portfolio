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
     * @params string $id
     * @return Photo
     */
    public function delete(string $id)
    {
        $photo = Photo::where('id', $id)->first();
        $post_user = $photo->postBy->user_id;

        if ((int) $post_user !== Auth::user()->id) {
            abort(401);
            return;
        }

        if (!$photo) {
            abort(404);
            exit;
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
     * @params string $id
     * @param StorePhoto $request
     * @return \Illuminate\Http\Response
     */
    public function create(string $id, StorePhoto $request)
    {
        $images = $request->post_photo;

        $post = Post::where('id', $id)->first();

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
                    $photo->post_id = $id;
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
