<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePost;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function update(UpdatePost $request)
    {
        if ((string) Auth::user()->login_id !== "guest001") {
            $user = User::find(Auth::user()->id);
            $user->fill($request->all())->save();

            $new_user = User::where('id', $user->id)->first();
            return $new_user;
        } else {
            abort(401);
        }
    }

    /**
     * ユーザー情報取得
     */
    public function show()
    {
        return response()->json(['login_id' => Auth::user()->login_id, 'name' => Auth::user()->name]);
    }
}
