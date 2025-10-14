<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Intranet\Models\PollAnswer;
use Faker\Generator as Faker;

$factory->define(PollAnswer::class, function (Faker $faker) {

    return [
        'answer' => $faker->text,
        'users_name' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'intranet_polls_id' => $faker->word,
        'intranet_polls_questions_id' => $faker->word,
        'users_id' => $faker->word
    ];
});
