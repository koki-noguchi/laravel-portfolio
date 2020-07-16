<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $visible = [
        'id', 'post_title', 'about', 'user',
    ];

    protected $hidden = [
        'user_id','post_password', 'min_number', 'max_number', 'share_judge',
        self::CREATED_AT, self::UPDATED_AT,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_title', 'about', 'share_judge',
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
