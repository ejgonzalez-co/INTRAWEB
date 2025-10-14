<?php

use Illuminate\Database\Seeder;
use Modules\Correspondence\Models\Internal;

class InternalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(Internal::class, 20000)->create();

        // factory(App\User::class, 50)->create()->each(function ($user) {
        //     $user->posts()->save(factory(App\Post::class)->make());
        // });

    }
}
