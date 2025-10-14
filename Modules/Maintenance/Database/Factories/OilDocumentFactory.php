<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\OilDocument;
use Faker\Generator as Faker;

$factory->define(OilDocument::class, function (Faker $faker) {

    return [
        'mant_oils_id' => $faker->word,
        'name' => $faker->word,
        'description' => $faker->text,
        'url_attachment' => $faker->text
    ];
});
