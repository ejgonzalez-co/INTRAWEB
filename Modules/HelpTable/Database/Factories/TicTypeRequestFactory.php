<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\HelpTable\Models\TicTypeRequest;
use Faker\Generator as Faker;

$factory->define(TicTypeRequest::class, function (Faker $faker) {

    return [
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'name' => $faker->word,
        'unit_time' => $faker->word,
        'type_term' => $faker->word,
        'term' => $faker->randomDigitNotNull,
        'early' => $faker->randomDigitNotNull,
        'description' => $faker->word
    ];
});
