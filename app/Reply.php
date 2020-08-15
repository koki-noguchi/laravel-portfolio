<?php

namespace App;
use App\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $visible = [
        'id', 'reply_user', 'reply_text', 'post_user', 'my_reply'
    ];

    protected $appends = [
        'post_user', 'my_reply'
    ];

    /**
     * アクセサ - post_user
     * @return boolean
     */
    public function getPostUserAttribute()
    {
        return (int) $this->message->post->user_id === $this->user_id;
    }

    /**
     * アクセサ - my_reply
     * @return boolean
     */
    public function getMyReplyAttribute()
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
