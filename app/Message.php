<?php

namespace App;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $visible = [
        'id', 'author', 'message_text', 'replies', 'my_message'
    ];

    protected $appends = [
        'my_message'
    ];

    /**
     * アクセサ - my_message
     * @return boolean
     */
    public function getMyMessageAttribute()
    {
        return (int) $this->user_id === Auth::user()->id;
    }

    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }

    /**
     * リレーションシップ - repliesテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany('App\Reply')->orderBy('id', 'desc');
    }

    /**
     * リレーションシップ - postsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('App\Post', 'post_id', 'id', 'posts');
    }

}
