<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $visible = [
        'id', 'post_title', 'about', 'user','my_post', 'password_judge', 'messages',
        'bookmarked_by_user', 'photos',
    ];

    protected $hidden = [
        'user_id','post_password', 'max_number', 'share_judge',
        self::CREATED_AT, self::UPDATED_AT,
    ];

    protected $appends = ['password_judge', 'bookmarked_by_user', 'my_post'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_title', 'about', 'share_judge',
    ];

    /**
     * アクセサ - password_judge
     * @return bool
     */
    public function getPasswordJudgeAttribute()
    {
        return $this->post_password ? true : false;
    }

    /**
     * アクセサ - bookmarked_by_user
     * @return boolean
     */
    public function getBookmarkedByUserAttribute()
    {
        if (Auth::guest()) {
            return false;
        }

        return $this->bookmarks->contains(function ($user) {
            return $user->id === Auth::user()->id;
        });
    }

    /**
     * アクセサ - my_post
     * @return boolean
     */
    public function getMyPostAttribute()
    {
        if (Auth::guest()) {
            return false;
        }

        return (int) $this->user_id === Auth::user()->id;
    }

    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }

    /**
     * リレーションシップ - messagesテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany('App\Message')->orderBy('id', 'desc');
    }

    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bookmarks()
    {
        return $this->belongsToMany('App\User', 'bookmarks')->withTimestamps();
    }

    /**
     * リレーションシップ - photosテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany('App\Photo');
    }
}
