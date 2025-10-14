<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Intranet\Models\TypeEvent;
use Faker\Generator as Faker;

$factory->define(TypeEvent::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->word,
        'state' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
