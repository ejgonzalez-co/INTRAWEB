<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\HelpTable\Models\TechnicalConcept;
use Faker\Generator as Faker;

$factory->define(TechnicalConcept::class, function (Faker $faker) {

    return [
        'id_staff_member' => $faker->word,
        'technician_id' => $faker->word,
        'reviewer_id' => $faker->word,
        'approver_id' => $faker->word,
        'equipment_type' => $faker->word,
        'equipment_mark' => $faker->word,
        'equipment_model' => $faker->word,
        'equipment_serial' => $faker->word,
        'inventory_plate' => $faker->word,
        'inventory_manager' => $faker->word,
        'status' => $faker->word,
        'consecutive' => $faker->word,
        'technical_concept' => $faker->text,
        'observations' => $faker->text,
        'date_issue_concept' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->word
    ];
});
