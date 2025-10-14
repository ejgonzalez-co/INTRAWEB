<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\RequestAnnotation;
use Faker\Generator as Faker;

$factory->define(RequestAnnotation::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'mant_sn_request_id' => $faker->word,
        'anotacion' => $faker->text,
        'name_user' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
