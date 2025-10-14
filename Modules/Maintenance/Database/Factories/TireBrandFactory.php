<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\TireBrand;
use Faker\Generator as Faker;

$factory->define(TireBrand::class, function (Faker $faker) {

    return [
        'brand_name' => $faker->word,
        'descripction' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
