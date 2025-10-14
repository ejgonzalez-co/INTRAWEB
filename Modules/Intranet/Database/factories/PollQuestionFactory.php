<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Intranet\Models\PollQuestion;
use Faker\Generator as Faker;

$factory->define(PollQuestion::class, function (Faker $faker) {

    return [
        'type' => $faker->word,
        'question' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'intranet_polls_id' => $faker->word
    ];
});
