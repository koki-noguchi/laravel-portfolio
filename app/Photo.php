<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    protected $visible = [
        'photos_url',
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

    const id_length = 12;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (! Arr::get($this->attributes, 'id')) {
            $this->setId();
        }
    }

    private function setId()
    {
        $this->attributes['id'] = $this->getRandomId();
    }

    /**
     * @return string
     */

    private function getRandomId()
    {
        $characters = array_merge(
        range(0, 9), range('a', 'z'),
        range('A', 'Z'), ['-', '_']
        );

        $length = count($characters);

        $id = "";

        for ($i = 0; $i < self::id_length; $i++) {
            $id .= $characters[random_int(0, $length - 1)];
        }

        return $id;
    }
}
