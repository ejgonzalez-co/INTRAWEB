<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\ImprovementPlans\Models\GoalProgress;
use Faker\Generator as Faker;

$factory->define(GoalProgress::class, function (Faker $faker) {

    return [
        'pm_goals_id' => $faker->word,
        'pm_goal_activities_id' => $faker->word,
        'goal_name' => $faker->word,
        'activity_name' => $faker->word,
        'activity_weigth' => $faker->word,
        'progress_weigth' => $faker->word,
        'evidence_description' => $faker->text,
        'url_progress_evidence' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
