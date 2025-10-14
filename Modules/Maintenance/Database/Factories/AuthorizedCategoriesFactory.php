<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\AuthorizedCategories;
use Faker\Generator as Faker;

$factory->define(AuthorizedCategories::class, function (Faker $faker) {

    return [
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'asset_authorization_id' => $faker->randomDigitNotNull,
        'mant_asset_type_id' => $faker->randomDigitNotNull,
        'mant_category_id' => $faker->randomDigitNotNull
    ];
});
