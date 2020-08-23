<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUser;
use App\User;
use App\Services\AuthorizationInterface;
use App\Services\PhotoInterface;
use App\Services\UserInterface;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
    }

    /**
     * ユーザーの取得
     * @param User $user
     * @return User
     */
    public function show(User $user)
    {
        if (!Auth::guest() && Auth::user()->id === $user->id) {
            $user->makeVisible(['login_id']);
        }
        return $user;
    }

    /**
     * ユーザー情報の更新
     * @param UpdateUser $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request,AuthorizationInterface $authorization, UserInterface $userUpdate, PhotoInterface $photoCreate)
    {
        $authorization->CheckGuest();

        $user = User::find(Auth::user()->id);
        $userUpdate->Update($user, $request->except(['user_image']));

        $file_base64 = $request->input('user_image');

        if ($file_base64 !== null) {
            $name = $userUpdate->EditImage($user);
            $photoCreate->PhotoSave($user, "", "profile_image", $name, $file_base64);

        } else {
            $userUpdate->InitializeImage($user);
        }
        $new_user = User::where('id', $user->id)->first();
        return $new_user;
    }

    /**
     * ユーザー情報の削除
     */
    public function delete(AuthorizationInterface $authorization)
    {
        $authorization->CheckGuest();
        User::where('id', Auth::id())->delete();
    }
}
