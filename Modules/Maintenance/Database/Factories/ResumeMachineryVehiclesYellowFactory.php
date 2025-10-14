<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Maintenance\Models\ResumeMachineryVehiclesYellow;
use Faker\Generator as Faker;

$factory->define(ResumeMachineryVehiclesYellow::class, function (Faker $faker) {

    return [
        'name_vehicle_machinery' => $faker->word,
        'no_inventory' => $faker->word,
        'purchase_price' => $faker->randomDigitNotNull,
        'sheet_elaboration_date' => $faker->date('Y-m-d H:i:s'),
        'mileage_start_activities' => $faker->randomDigitNotNull,
        'mark' => $faker->word,
        'model' => $faker->word,
        'no_motor' => $faker->word,
        'invoice_number' => $faker->word,
        'date_put_into_service' => $faker->date('Y-m-d H:i:s'),
        'warranty_date' => $faker->date('Y-m-d H:i:s'),
        'warranty_description' => $faker->word,
        'service_retirement_date' => $faker->date('Y-m-d H:i:s'),
        'warehouse_entry_number' => $faker->word,
        'warehouse_exit_number' => $faker->word,
        'delivery_date_vehicle_by_provider' => $faker->date('Y-m-d H:i:s'),
        'plaque' => $faker->word,
        'color' => $faker->word,
        'chassis_number' => $faker->word,
        'service_class' => $faker->word,
        'body_type' => $faker->word,
        'transit_license_number' => $faker->word,
        'number_passengers' => $faker->randomDigitNotNull,
        'fuel_type' => $faker->word,
        'number_tires' => $faker->randomDigitNotNull,
        'front_tire_reference' => $faker->word,
        'rear_tire_reference' => $faker->word,
        'number_batteries' => $faker->randomDigitNotNull,
        'battery_reference' => $faker->word,
        'gallon_tank_capacity' => $faker->randomDigitNotNull,
        'tons_capacity_load' => $faker->randomDigitNotNull,
        'cylinder_capacity' => $faker->word,
        'expiration_date_soat' => $faker->date('Y-m-d H:i:s'),
        'expiration_date_tecnomecanica' => $faker->date('Y-m-d H:i:s'),
        'person_prepares_resume' => $faker->word,
        'person_reviewed_approved' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'dependencias_id' => $faker->word,
        'mant_category_id' => $faker->randomDigitNotNull
    ];
});
