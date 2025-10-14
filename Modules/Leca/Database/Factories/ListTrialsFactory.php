<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Leca\Models\ListTrials;
use Faker\Generator as Faker;

$factory->define(ListTrials::class, function (Faker $faker) {

    return [
        'type' => $faker->word,
        'number_list' => $faker->word,
        'code' => $faker->word,
        'name' => $faker->word,
        'description' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
