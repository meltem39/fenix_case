<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'GOLD',
                'fee' => 999.99,
                'currency_unit' => "TL",
                'quota' => 6000,
                'description' => "Satın aldıktan sonra 6000 kullanıma kadar CHATBOT'un tadını çıkar."
            ],
            [
                'name' => 'SILVER',
                'fee' => 499.99,
                'currency_unit' => "TL",
                'quota' => 2500,
                'description' => "Satın aldıktan sonra 2500 kullanıma kadar CHATBOT'un tadını çıkar."

            ],
            [
                'name' => 'BASIC',
                'fee' => 150,
                'currency_unit' => "TL",
                'quota' => 500,
                'description' => "Satın aldıktan sonra 500 kullanıma kadar CHATBOT'un tadını çıkar."

            ],
            [
                'name' => 'FREE',
                'fee' => 0,
                'currency_unit' => "TL",
                'quota' => 5,
                'description' => "Ücretsiz 5 chat hakkın bittikten sonra satın almayı unutma."

            ],
        ];

        foreach ($packages as $package) {
            Package::insert($package);
        }
    }
}
