<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\AssetCreateAuthorization;
use Faker\Generator as Faker;

$factory->define(AssetCreateAuthorization::class, function (Faker $faker) {

    return [
        'responsable' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'dependencias_id' => $faker->word
    ];
});
