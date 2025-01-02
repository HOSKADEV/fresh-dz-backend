<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Admin::create([
          'name' => 'super admin',
          'phone' => '0123456789',
          'email' => 'super@admin.com',
          'password' => \Illuminate\Support\Facades\Hash::make('123456789'),
          'status' => 1
        ]);
    }
}
