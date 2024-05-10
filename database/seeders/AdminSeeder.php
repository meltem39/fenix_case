<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name_surname' => 'Fenix Mobile Admin',
                'email' => 'admin@fenixmobile.com',
                'password' => Hash::make('123456'),
                'status' => "admin"
            ],
            [
                'name_surname' => 'Fenix Mobile Worker',
                'email' => 'worker@fenixmobile.com',
                'password' => Hash::make('123456'),
                'status' => "worker"
            ]
        ];

        foreach ($admins as $admin) {
            Admin::insert($admin);
        }
    }
}
