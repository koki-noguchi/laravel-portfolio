<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUser;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
     * ユーザー情報取得
     */
    public function show()
    {
        return response()->json([
            'login_id' => Auth::user()->login_id,
            'name' => Auth::user()->name,
            'url' => Auth::user()->url,
        ]);
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
     * ブックマークの取得
     */
    public function bookmark()
    {
        $bookmark = Auth::user()->bookmark_post;
        $bookmark->load('photos', 'user');

        if ($bookmark) {
            return $bookmark;
        } else {
            abort(404);
        }
    }
}
