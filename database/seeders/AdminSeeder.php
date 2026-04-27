<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create admin 
        Admin::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('123456789')
            ]
        );
    }
}
