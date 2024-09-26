<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'root',
            'email' => 'root@root.it',
            'password' => Hash::make('root@123')
        ]);
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.it',
            'password' => Hash::make('admin@123')
        ]);

    }
}
