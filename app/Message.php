<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $visible = [
        'author', 'message_text',
    ];

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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function reply()
    {
        return $this->hasOne('App\Reply')->orderBy('id', 'desc');
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
