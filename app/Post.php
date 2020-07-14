<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $visible = [
        'post_title', 'about',
    ];

    protected $hidden = [
        'id','user_id','post_password', 'min_number', 'max_number', 'share_judge',
        self::CREATED_AT, self::UPDATED_AT,
    ];

    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }
}
