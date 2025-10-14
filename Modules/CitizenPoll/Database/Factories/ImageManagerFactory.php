<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\CitizenPoll\Models\ImageManager;
use Faker\Generator as Faker;

$factory->define(ImageManager::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'url_image' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
