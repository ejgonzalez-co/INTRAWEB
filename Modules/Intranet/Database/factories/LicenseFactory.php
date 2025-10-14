<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Intranet\Models\License;
use Faker\Generator as Faker;

$factory->define(License::class, function (Faker $faker) {

    return [
        'title' => $faker->word,
        'alias' => $faker->word,
        'description' => $faker->text,
        'checked_out' => $faker->randomDigitNotNull,
        'checked_out_time' => $faker->date('Y-m-d H:i:s'),
        'published' => $faker->word,
        'ordering' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
