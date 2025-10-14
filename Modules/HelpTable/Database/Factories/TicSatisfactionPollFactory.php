<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\HelpTable\Models\TicSatisfactionPoll;
use Faker\Generator as Faker;

$factory->define(TicSatisfactionPoll::class, function (Faker $faker) {

    return [
        'ht_tic_requests_id' => $faker->word,
        'users_id' => $faker->word,
        'functionary_id' => $faker->word,
        'question' => $faker->text,
        'reply' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
