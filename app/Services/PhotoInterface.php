<?php

namespace App\Services;

interface PhotoInterface
{
    public function NewFileName($image);
    public function Delete($image);
    public function PhotoSave($tableTo, $tableRelation, $fileTo, $fileName, $fileData);
}