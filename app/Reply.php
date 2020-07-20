<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $visible = [
        'id', 'reply_user', 'reply_text', 'reply_judge',
    ];

    protected $appends = [
        'reply_judge',
    ];

    /**
     * アクセサ - reply_judge
     * @return boolean
     */
    public function getReplyJudgeAttribute()
    {
        return (int) $this->message->post->user_id === $this->user_id;
    }

    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reply_user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }

    /**
     * リレーションシップ - messagesテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo('App\Message', 'message_id', 'id', 'messages');
    }
}
