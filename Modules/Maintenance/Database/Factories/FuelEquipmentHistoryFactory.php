<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\FuelEquipmentHistory;
use Faker\Generator as Faker;

$factory->define(FuelEquipmentHistory::class, function (Faker $faker) {

    return [
        'users_id' => $faker->word,
        'date_register' => $faker->word,
        'description' => $faker->word,
        'action' => $faker->word,
        'name_user' => $faker->word,
        'dependencia' => $faker->word,
        'id_equipment_fuel' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
