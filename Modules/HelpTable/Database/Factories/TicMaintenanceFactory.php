<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\HelpTable\Models\TicMaintenance;
use Faker\Generator as Faker;

$factory->define(TicMaintenance::class, function (Faker $faker) {

    return [
        'ht_tic_assets_id' => $faker->word,
        'ht_tic_provider_id' => $faker->word,
        'ht_tic_requests_id' => $faker->word,
        'dependencias_id' => $faker->word,
        'type_maintenance' => $faker->word,
        'fault_description' => $faker->word,
        'service_start_date' => $faker->word,
        'end_date_service' => $faker->word,
        'maintenance_description' => $faker->word,
        'contract_number' => $faker->word,
        'cost' => $faker->randomDigitNotNull,
        'warranty_start_date' => $faker->word,
        'warranty_end_date' => $faker->word,
        'maintenance_status' => $faker->word,
        'provider_name' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
