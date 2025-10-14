<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\HelpTable\Models\TicRequest;
use Faker\Generator as Faker;

$factory->define(TicRequest::class, function (Faker $faker) {

    return [
        'ht_tic_type_request_id' => $faker->word,
        'ht_tic_request_status_id' => $faker->word,
        'assigned_by_id' => $faker->word,
        'users_id' => $faker->word,
        'assigned_user_id' => $faker->word,
        'ht_tic_type_tic_categories_id' => $faker->word,
        'priority_request' => $faker->word,
        'affair' => $faker->text,
        'floor' => $faker->word,
        'assignment_date' => $faker->date('Y-m-d H:i:s'),
        'prox_date_to_expire' => $faker->date('Y-m-d H:i:s'),
        'expiration_date' => $faker->date('Y-m-d H:i:s'),
        'date_attention' => $faker->date('Y-m-d H:i:s'),
        'closing_date' => $faker->date('Y-m-d H:i:s'),
        'reshipment_date' => $faker->date('Y-m-d H:i:s'),
        'next_hour_to_expire' => $faker->text,
        'hours' => $faker->text,
        'description' => $faker->text,
        'tracing' => $faker->text,
        'request_status' => $faker->word,
        'survey_status' => $faker->word,
        'time_line' => $faker->word,
        'support_type' => $faker->word,
        'assigned_by_name' => $faker->word,
        'users_name' => $faker->word,
        'assigned_user_name' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
