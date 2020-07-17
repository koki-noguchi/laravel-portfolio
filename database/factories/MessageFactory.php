<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Message::class, function (Faker $faker) {
    return [
        'message_text' => substr($faker->text, 0, 500),
        'user_id' => fn() => factory(App\User::class)->create()->id,
    ];
});
