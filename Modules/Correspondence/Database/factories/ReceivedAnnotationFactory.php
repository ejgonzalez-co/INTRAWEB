<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Correspondence\Models\ReceivedAnnotation;
use Faker\Generator as Faker;

$factory->define(ReceivedAnnotation::class, function (Faker $faker) {

    return [
        'external_received_id' => $faker->word,
        'user_id' => $faker->word,
        'user_name' => $faker->word,
        'annotation' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
