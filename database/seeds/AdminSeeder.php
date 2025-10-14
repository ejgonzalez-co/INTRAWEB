<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => "Administrador", 
            'email' => "admin@ceropapeles.com", 
            'email_verified_at' => date("Y-m-d"), 
            'password' => Hash::make("123456")
        ]);
    }
}
