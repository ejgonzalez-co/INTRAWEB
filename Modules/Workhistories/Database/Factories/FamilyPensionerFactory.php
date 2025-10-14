<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Workhistories\Models\FamilyPensioner;
use Faker\Generator as Faker;

$factory->define(FamilyPensioner::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'gender' => $faker->word,
        'birth_date' => $faker->word,
        'type' => $faker->word,
        'state' => $faker->word,
        'observation' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'users_id' => $faker->word,
        'work_histories_p_id' => $faker->word
    ];
});
