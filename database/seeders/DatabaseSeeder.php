<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UserTableSeeder::class);

        // \App\Models\User::factory(10)->create();

        User::create([
		    'full_name' => 'super admin',
		    'email' => 'admin2022@gmail.com',
            'phone_no' => 0740100005,
		    'password' => \Illuminate\Support\Facades\Hash::make('admin'),
		    'role' => '*'
	    ]);
    }
}
