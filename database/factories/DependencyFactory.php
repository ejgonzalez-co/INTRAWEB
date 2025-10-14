<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Dependency;
use Faker\Generator as Faker;

$factory->define(Dependency::class, function (Faker $faker) {

    return [
        'dependencies_id' => $faker->word,
        'headquarters_id' => $faker->word,
        'code' => $faker->word,
        'name' => $faker->word,
        'description' => $faker->word,
        'producer_office_code' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
