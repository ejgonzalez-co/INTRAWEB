<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\HelpTable\Models\TicTypeAsset;
use Faker\Generator as Faker;

$factory->define(TicTypeAsset::class, function (Faker $faker) {

    return [
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'name' => $faker->word,
        'description' => $faker->word,
        'ht_tic_type_tic_categories_id' => $faker->word
    ];
});
