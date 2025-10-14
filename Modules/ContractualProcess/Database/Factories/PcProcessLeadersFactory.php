<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ContractualProcess\Models\ProcessLeaders;
use Faker\Generator as Faker;

$factory->define(ProcessLeaders::class, function (Faker $faker) {

    return [
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'leader_name' => $faker->word,
        'name_process' => $faker->word,
        'users_id' => $faker->word
    ];
});
