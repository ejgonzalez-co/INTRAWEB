<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ContractualProcess\Models\PaaCall;
use Faker\Generator as Faker;

$factory->define(PaaCall::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'validity' => $faker->word,
        'name' => $faker->word,
        'start_date' => $faker->date('Y-m-d H:i:s'),
        'closing_alert_date' => $faker->date('Y-m-d H:i:s'),
        'closing_date' => $faker->date('Y-m-d H:i:s'),
        'observation_message' => $faker->text,
        'state' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
