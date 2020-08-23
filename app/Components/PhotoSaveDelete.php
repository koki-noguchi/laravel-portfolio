<?php

namespace App\Components;

use App\Services\PhotoInterface;
use App\Photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PhotoSaveDelete implements PhotoInterface
{
    public function NewFileName($image)
    {
        $photo = new Photo();

        $extension = $image->extension();
        $photo->post_photo = $photo->id . '.' . $extension;

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

    public function PhotoSave($tableTo, $tableRelation, $fileTo, $fileName, $fileData)
    {
        Storage::cloud()->putFileAs($fileTo, $fileData, $fileName, 'public');

        $fileName = $fileTo . "/" . $fileName;

        DB::beginTransaction();

        try {
            if ($tableTo->post_title) {
                $tableRelation->post_photo = $fileName;
                $tableTo->photos()->save($tableRelation);

            } elseif ($tableTo->post_photo) {
                $tableTo->post_photo = $fileName;
                $tableTo->post_id = $tableRelation->id;
                $tableTo->save();

            } elseif ($tableTo->user_image) {
                $tableTo->user_image = $fileName;
                $tableTo->save();
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            Storage::cloud()->delete($fileName);
            throw $exception;
        }
    }
}