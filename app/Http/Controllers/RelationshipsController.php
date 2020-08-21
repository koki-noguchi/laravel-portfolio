<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RelationshipsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('followList', 'followerList');
    }

    /**
     * フォローリスト
     * @param User $user
     * @return array
     */
    public function followList(User $user)
    {
        $user->load(['followings', 'followings.followings', 'followings.followers']);

        $user->makeVisible(['followings']);
        return $user;
    }

    /**
     * フォロワーリスト
     * @param User $user
     * @return array
     */
    public function followerList(User $user)
    {
        $user->load(['followers', 'followers.followings', 'followers.followers']);

        $user->makeVisible(['followers']);
        return $user;
    }

    /**
     * フォロー
     * @param User $user
     * @return array
     */
    public function follow(User $user)
    {
        $auth_user = User::where('id', Auth::user()->id)->with('followings')->first();

        $auth_user->followings()->detach($user->id);
        $auth_user->followings()->attach($user->id);

        return ["followee_id" => (int) $user->id];
    }

    /**
     * フォローを外す
     * @param User $user
     * @return array
     */
    public function deleteFollow(User $user)
    {
        $auth_user = User::where('id', Auth::user()->id)->with('followings')->first();

        $auth_user->followings()->detach($user->id);

        return ["followee_id" => (int) $user->id];
    }
}
