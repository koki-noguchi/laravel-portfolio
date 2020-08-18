<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'login_id', 'password', 'permission_id', 'user_image',
    ];

    protected $visible = [
        'name', 'url', 'id', 'followed_judge', 'follow_count', 'follower_count'
    ];

    protected $appends = [
        'url', 'followed_judge', 'follow_count', 'follower_count'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * アクセサ - url
     * @return string
     */
    public function getUrlAttribute()
    {
        if (empty($this->user_image)) {
            return '/images/default-image.jpeg';
        }

        if ($this->user_image === '/images/default-image.jpeg') {
            return $this->user_image;
        } else {
            return Storage::cloud()->url($this->attributes['user_image']);
        }
    }

    /**
     * アクセサ - followed_judge
     * @return boolean
     */
    public function getFollowedJudgeAttribute()
    {
        if (Auth::guest()) {
            return false;
        }

        return $this->followers->contains(function ($user) {
            return $user->id === Auth::user()->id;
        });
    }

    /**
     * アクセサ - follower_judge
     * @return boolean
     */
    public function getFollowerJudgeAttribute() {
        if (Auth::guest()) {
            return false;
        }

        return $this->followings->contains(function ($user) {
            return $user->id === Auth::user()->id;
        });
    }

    /**
     * アクセサ - follow_count
     * @return int
     */
    public function getFollowCountAttribute()
    {
        return $this->followings->count();
    }

    /**
     * アクセサ - follower_count
     * @return int
     */
    public function getFollowerCountAttribute()
    {
        return $this->followers->count();
    }

    /**
     * リレーションシップ - postsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Post')->orderBy('created_at', 'desc');
    }

    /**
     * リレーションシップ - postsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bookmark_post()
    {
        return $this->belongsToMany('App\Post', 'bookmarks')->withTimestamps();
    }

    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followings()
    {
        return $this->belongsToMany('App\User', 'relationships', 'follower_id', 'followee_id')->withTimestamps();
    }

    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany('App\User', 'relationships', 'followee_id', 'follower_id')->withTimestamps();
    }
}
