<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Intranet\Models\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {

    return [
        'intranet_type_events_id' => $faker->word,
        'type_category' => $faker->word,
        'title' => $faker->word,
        'description' => $faker->word,
        'initial_date' => $faker->date('Y-m-d H:i:s'),
        'initial_hour' => $faker->word,
        'end_hour' => $faker->word,
        'duration' => $faker->word,
        'state' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
