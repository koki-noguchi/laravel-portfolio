<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $visible = [
        'id', 'post_title', 'about', 'user', 'password_judge', 'messages',
    ];

    protected $hidden = [
        'user_id','post_password', 'min_number', 'max_number', 'share_judge',
        self::CREATED_AT, self::UPDATED_AT,
    ];

    protected $appends = ['password_judge'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_title', 'about', 'share_judge',
    ];

    /**
     * ユーザーの管理者フラグを取得
     *
     * @return bool
     */
    public function getPasswordJudgeAttribute()
    {
        return $this->post_password ? true : false;
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
}
