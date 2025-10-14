<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\OilElementWearConfiguration;
use Faker\Generator as Faker;

$factory->define(OilElementWearConfiguration::class, function (Faker $faker) {

    return [
        'register_date' => $faker->date('Y-m-d H:i:s'),
        'element_name' => $faker->word,
        'rank_higher' => $faker->randomDigitNotNull,
        'rank_lower' => $faker->randomDigitNotNull,
        'observation' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
