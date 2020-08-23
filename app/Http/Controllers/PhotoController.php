<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Post;
use App\Http\Requests\StorePhoto;
use App\Services\AuthorizationInterface;
use App\Services\PhotoInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * 画像の削除
     * @param Photo $photo
     * @return Photo
     */
    public function delete(Photo $photo, AuthorizationInterface $authorization, PhotoInterface $photoDelete)
    {
        $authorization->Check((int) $photo->postBy->user_id);

        $photoDelete->Delete([$photo]);
    }

    /**
     * 画像追加
     * @param Post $post
     * @param StorePhoto $request
     * @return \Illuminate\Http\Response
     */
    public function create(Post $post, StorePhoto $request, AuthorizationInterface $authorization, PhotoInterface $photoCreate)
    {
        $images = $request->post_photo;

        $authorization->Check((int) $post->user_id);

        if ($images) {
            foreach ($images as $image ) {

                $photo = $photoCreate->Create($image);
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
