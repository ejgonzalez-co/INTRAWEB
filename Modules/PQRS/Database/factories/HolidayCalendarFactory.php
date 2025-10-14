<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\PQRS\Models\HolidayCalendar;
use Faker\Generator as Faker;

$factory->define(HolidayCalendar::class, function (Faker $faker) {

    return [
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'date' => $faker->date('Y-m-d H:i:s')
    ];
});
