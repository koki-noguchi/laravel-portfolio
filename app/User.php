<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

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
        'name', 'url', 'id'
    ];

    protected $appends = [
        'url',
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
     * リレーションシップ - postsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    /**
     * リレーションシップ - postsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bookmark_post()
    {
        return $this->belongsToMany('App\Post', 'bookmarks')->withTimestamps();
    }
}
