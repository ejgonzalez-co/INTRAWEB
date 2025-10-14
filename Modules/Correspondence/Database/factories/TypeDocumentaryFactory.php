<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Correspondence\Models\TypeDocumentary;
use Faker\Generator as Faker;

$factory->define(TypeDocumentary::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'state' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
