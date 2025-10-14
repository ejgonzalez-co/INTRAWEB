<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\HelpTable\Models\TicAsset;
use Faker\Generator as Faker;

$factory->define(TicAsset::class, function (Faker $faker) {

    return [
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'consecutive' => $faker->word,
        'name' => $faker->word,
        'brand' => $faker->word,
        'serial' => $faker->word,
        'model' => $faker->word,
        'inventory_plate' => $faker->word,
        'description' => $faker->word,
        'general_description' => $faker->word,
        'purchase_date' => $faker->word,
        'location_address' => $faker->word,
        'state' => $faker->word,
        'monitor_id' => $faker->word,
        'keyboard_id' => $faker->word,
        'mouse_id' => $faker->word,
        'operating_system' => $faker->word,
        'operating_system_version' => $faker->word,
        'operating_system_serial' => $faker->word,
        'license_microsoft_office' => $faker->word,
        'serial_licencia_microsoft_office' => $faker->word,
        'processor' => $faker->word,
        'ram' => $faker->word,
        'hdd' => $faker->word,
        'name_user' => $faker->word,
        'provider_name' => $faker->word,
        'ht_tic_period_validity_id' => $faker->word,
        'ht_tic_type_assets_id' => $faker->word,
        'ht_tic_provider_id' => $faker->word,
        'users_id' => $faker->word,
        'dependencias_id' => $faker->word
    ];
});
