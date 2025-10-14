<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Configuracion\Models\Variables;
use Faker\Generator as Faker;

$factory->define(Variables::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'value' => $faker->word,
        'type' => $faker->word,
        'description' => $faker->text
    ];
});
