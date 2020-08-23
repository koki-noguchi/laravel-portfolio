<?php

namespace App;

use App\Traits\RandomId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use RandomId;

    protected $visible = [
        'id', 'photos_url',
    ];

    protected $appends = [
        'photos_url',
    ];

    /**
     * アクセサ - photos_url
     * @return boolean
     */
    public function getPhotosUrlAttribute()
    {
        return Storage::cloud()->url($this->attributes['post_photo']);
    }

    protected $keyType = 'string';

    // const id_length = 12;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (! Arr::get($this->attributes, 'id')) {
            $this->setId();
        }
    }

    private function setId()
    {
        $this->attributes['id'] = $this->random_id;
    }

    /**
     * リレーションシップ - postsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function postBy()
    {
        return $this->belongsTo('App\Post', 'post_id', 'id', 'posts');
    }
}
