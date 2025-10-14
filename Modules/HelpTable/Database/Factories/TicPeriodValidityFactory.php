<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\HelpTable\Models\TicPeriodValidity;
use Faker\Generator as Faker;

$factory->define(TicPeriodValidity::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
