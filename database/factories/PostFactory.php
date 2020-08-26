<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'post_title' => $faker->word,
        'post_password' => $faker->password,
        'max_number' => 2,
        'created_at' => $faker->dateTime(),
        'updated_at' => $faker->dateTime(),
        'about' => $faker->word,
    ];
});
