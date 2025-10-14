<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\SetTire;
use Faker\Generator as Faker;

$factory->define(SetTire::class, function (Faker $faker) {

    return [
        'mant_tire_brand_id' => $faker->word,
        'registration_date' => $faker->word,
        'tire_reference' => $faker->word,
        'maximum_wear' => $faker->randomDigitNotNull,
        'observation' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
