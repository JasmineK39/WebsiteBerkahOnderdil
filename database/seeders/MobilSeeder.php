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
<<<<<<< HEAD
            ['brand' => 'Daihatsu', 'model' => 'Avanza', 'image' => 'DaihatsuAvanza.png'],
            ['brand' => 'Honda', 'model' => 'Jazz', 'image' => 'Honda-Jazz.png'],
            ['brand' => 'Suzuki', 'model' => 'Swift', 'image' => 'Suzuki_Swift.png'],
            ['brand' => 'Mitsubishi', 'model' => 'Xpander', 'image' => 'Mitsubishi_Xpander.png'],
            ['brand' => 'Nissan', 'model' => 'Livina', 'image' => 'Nissan_Livina.png'],
            ['brand' => 'Ford', 'model' => 'EcoSport', 'image' => 'Ford_EcoSport.png'],
            ['brand' => 'Chevrolet', 'model' => 'Captiva', 'image' => 'Chevrolet-Captiva.png'],
            ['brand' => 'BMW', 'model' => 'X1', 'image' => ''],
=======
            
>>>>>>> a3f620247b6426ab1d9ed666e87477c3eef32f1b
        ];

        foreach ($mobils as $m) {
            ModelMobil::create($m);
        }
    }
}
