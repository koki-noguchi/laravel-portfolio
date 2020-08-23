<?php

namespace App\Components;

use App\Services\PhotoInterface;
use App\Photo;
use Illuminate\Support\Facades\Storage;

class PhotoSaveDelete implements PhotoInterface
{
    public function Create($image)
    {
        $photo = new Photo();

        $extension = $image->extension();
        $photo->post_photo = $photo->id . '.' . $extension;

        Storage::cloud()->putFileAs('post_photo', $image, $photo->post_photo, 'public');
        $photo->post_photo = 'post_photo/' . $photo->id . '.' . $extension;

        return $photo;
    }

    public function Delete($images)
    {
        foreach ($images as $photo) {
            if (Storage::cloud()->exists($photo->post_photo)) {
                Storage::cloud()->delete($photo->post_photo);
                $photo->delete();
            }
        }
    }
}