<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Intranet\Models\Poll;
use Faker\Generator as Faker;

$factory->define(Poll::class, function (Faker $faker) {

    return [
        'title' => $faker->text,
        'description' => $faker->text,
        'date_open' => $faker->word,
        'date_close' => $faker->word,
        'creator' => $faker->word,
        'state' => $faker->word,
        'users_name' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'users_id' => $faker->word
    ];
});
