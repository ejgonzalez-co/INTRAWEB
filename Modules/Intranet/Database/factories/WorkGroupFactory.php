<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Intranet\Models\WorkGroup;
use Faker\Generator as Faker;

$factory->define(WorkGroup::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->word,
        'state' => $faker->word,
        'url_img_profile' => $faker->word,
        'end_date' => $faker->date('Y-m-d H:i:s'),
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
