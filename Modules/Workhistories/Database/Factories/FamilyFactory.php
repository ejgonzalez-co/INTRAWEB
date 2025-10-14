<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Workhistories\Models\Family;
use Faker\Generator as Faker;

$factory->define(Family::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'gender' => $faker->word,
        'birth_date' => $faker->word,
        'type' => $faker->word,
        'state' => $faker->randomDigitNotNull,
        'users_id' => $faker->word,
        'work_histories_id' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
