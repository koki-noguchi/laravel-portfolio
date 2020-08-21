<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUser;
use Illuminate\Http\Request;
use App\User;
use App\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('profile', 'followList');
    }

    /**
     * ユーザー情報の更新
     * @param UpdateUser $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request)
    {
        if ((string) Auth::user()->login_id === "guest001") {
            abort(401);
            exit;
        }

        $user = User::find(Auth::user()->id);
        $user->fill($request->except(['user_image']));

        $file_base64 = $request->input('user_image');
        if ($user->user_image !== '/images/default-image.jpeg') {
            Storage::cloud()->delete($user->user_image);
        }

        if ($file_base64 !== null) {
            list(, $fileData) = explode(';', $file_base64);
            list(, $fileData) = explode(',', $fileData);
            $fileData = base64_decode($fileData);
            $fileName= Str::random(12).'.jpeg';
            $path = 'profile_image/'.$fileName;

            Storage::cloud()->put($path, $fileData, 'public');

            DB::beginTransaction();

            try {
                $user->user_image = $path;
                $user->save();
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();

                Storage::cloud()->delete($fileData);
                throw $exception;
            }
        } else {
            $user->user_image = '/images/default-image.jpeg';
            $user->save();
        }
        $new_user = User::where('id', $user->id)->first();
        return $new_user;
    }

    /**
     * ユーザー情報の削除
     */
    public function delete()
    {
        if ((string) Auth::user()->login_id !== "guest001") {
            User::where('id', Auth::id())->delete();
        } else {
            abort(401);
        }
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
     * フォロー
     * @params string $id
     * @return array
     */
    public function follow(string $id)
    {
        $user = User::where('id', Auth::user()->id)->with('followings')->first();

        if (! $user) {
            abort(404);
        }

        $user->followings()->detach($id);
        $user->followings()->attach($id);

        return ["followee_id" => (int) $id];
    }

    /**
     * フォローを外す
     * @params string $id
     * @return array
     */
    public function deleteFollow(string $id)
    {
        $user = User::where('id', Auth::user()->id)->with('followings')->first();

        if (! $user) {
            abort(404);
        }

        $user->followings()->detach($id);

        return ["followee_id" => (int) $id];
    }

    /**
     * フォロー
     * @params string $id
     * @return array
     */
    public function followList(string $id)
    {
        $user = User::where('id', $id)
            ->with(['followings', 'followers'])->first();

        if (! $user) {
            abort(404);
        }

        $user->makeVisible(['followings', 'followers']);
        return $user;
    }
}
