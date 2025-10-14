<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Correspondence\Models\InternalAnnotation;
use Faker\Generator as Faker;

$factory->define(InternalAnnotation::class, function (Faker $faker) {

    return [
        'content' => $faker->text,
        'users_name' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'correspondence_internal_id' => $faker->word
    ];
});
