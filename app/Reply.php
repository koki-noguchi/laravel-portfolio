<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $visible = [
        'reply_user', 'reply_text',
    ];

    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reply_user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }
}
