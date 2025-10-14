<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\MinorEquipmentFuel;
use Faker\Generator as Faker;

$factory->define(MinorEquipmentFuel::class, function (Faker $faker) {

    return [
        'dependencias_id' => $faker->word,
        'users_id' => $faker->word,
        'responsible_process' => $faker->word,
        'supply_date' => $faker->word,
        'supply_hour' => $faker->word,
        'start_date_fortnight' => $faker->word,
        'end_date_fortnight' => $faker->word,
        'initial_fuel_balance' => $faker->randomDigitNotNull,
        'more_buy_fortnight' => $faker->randomDigitNotNull,
        'less_fuel_deliveries' => $faker->randomDigitNotNull,
        'final_fuel_balance' => $faker->randomDigitNotNull,
        'bill_number' => $faker->word,
        'gallon_value' => $faker->randomDigitNotNull,
        'checked_fuel' => $faker->randomDigitNotNull,
        'cost_in_pesos' => $faker->randomDigitNotNull,
        'name' => $faker->word,
        'position' => $faker->word,
        'approved_process' => $faker->word,
        'process_leader_name' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
