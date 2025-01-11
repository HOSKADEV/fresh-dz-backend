<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\Admin;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
          'name' => 'super admin',
          'phone' => '0123456789',
          'email' => 'super@admin.com',
          'password' => \Illuminate\Support\Facades\Hash::make('123456789'),
          'status' => 1
        ]);
    }
}
