<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\HelpTable\Models\TicPollQuestion;
use Faker\Generator as Faker;

$factory->define(TicPollQuestion::class, function (Faker $faker) {

    return [
        'question_number' => $faker->word,
        'question' => $faker->word,
        'answer_option' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
