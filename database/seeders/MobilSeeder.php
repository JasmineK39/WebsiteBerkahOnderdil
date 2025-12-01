<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModelMobil;

class MobilSeeder extends Seeder
{
    public function run()
    {
        $mobils = [
            ['brand' => 'Toyota', 'model' => 'Kijang Innova', 'image' => 'kijangInova.png'],

        ];

        foreach ($mobils as $m) {
            ModelMobil::create($m);
        }
    }
}
