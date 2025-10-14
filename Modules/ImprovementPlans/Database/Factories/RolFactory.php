<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ImprovementPlans\Models\Rol;
use Faker\Generator as Faker;

$factory->define(Rol::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'guard_name' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
