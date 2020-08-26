<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(App\Photo::class, function (Faker $faker) {
    return [
        'id' => Str::random(12),
        'post_id' => function () {
            return factory(App\Post::class)->create()->id;
        },
        'post_photo' => Str::random(12) . '.jpg',
        'created_at' => $faker->dateTime(),
        'updated_at' => $faker->dateTime(),
    ];
});
