<?php

namespace App\Http\Controllers;

use App\Photo;
use Illuminate\Http\Request;
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
}
