<?php

namespace App\Components;

use App\Services\UserInterface;
use Illuminate\Support\Facades\Storage;

class UserUpdate implements UserInterface
{
    public function Update($user, $params)
    {
        $user->fill($params);

        if ($user->user_image !== '/images/default-image.jpeg') {
            Storage::cloud()->delete($user->user_image);
        }

        return $user;
    }
    public function EditImage($user)
    {
        $fileName = $user->random_id.'.jpeg';

        return $fileName;
    }

    public function InitializeImage($user)
    {
        $user->user_image = '/images/default-image.jpeg';
        $user->save();
    }


}