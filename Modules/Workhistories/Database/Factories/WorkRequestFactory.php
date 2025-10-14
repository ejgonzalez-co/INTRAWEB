<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Workhistories\Models\WorkRequest;
use Faker\Generator as Faker;

$factory->define(WorkRequest::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'work_histories_id' => $faker->word,
        'work_histories_p_id' => $faker->word,
        'work_histories_p_users_id' => $faker->word,
        'user_id' => $faker->word,
        'user_name' => $faker->word,
        'consultation_time' => $faker->word,
        'answer' => $faker->text,
        'reason_consultation' => $faker->text,
        'condition' => $faker->word,
        'date_start' => $faker->word,
        'date_final' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
