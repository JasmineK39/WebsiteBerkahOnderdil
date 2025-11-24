<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModelMobil;

class MobilSeeder extends Seeder
{
    public function run()
    {
        $mobils = [
            ['brand' => 'Toyota', 'model' => 'Kijang Innova', 'year' => 2015, 'description' => 'MPV keluarga', 'image' => 'kijangInova.png'],
            ['brand' => 'Daihatsu', 'model' => 'Avanza', 'year' => 2017, 'description' => 'Mobil keluarga populer', 'image' => 'DaihatsuAvanza.png'],
            ['brand' => 'Honda', 'model' => 'Jazz', 'year' => 2016, 'description' => 'Hatchback sporty', 'image' => 'Honda-Jazz.png'],
            ['brand' => 'Suzuki', 'model' => 'Swift', 'year' => 2018, 'description' => 'Hatchback kompak', 'image' => 'Suzuki_Swift.png'],
            ['brand' => 'Mitsubishi', 'model' => 'Xpander', 'year' => 2019, 'description' => 'MPV modern', 'image' => 'Mitsubishi_Xpander.png'],
            ['brand' => 'Nissan', 'model' => 'Livina', 'year' => 2020, 'description' => 'MPV stylish', 'image' => 'Nissan_Livina.png'],
            ['brand' => 'Ford', 'model' => 'EcoSport', 'year' => 2015, 'description' => 'SUV kompak', 'image' => 'Ford_EcoSport.png'],
            ['brand' => 'Chevrolet', 'model' => 'Captiva', 'year' => 2016, 'description' => 'SUV keluarga', 'image' => 'Chevrolet-Captiva.png'],
            ['brand' => 'BMW', 'model' => 'X1', 'year' => 2018, 'description' => 'SUV mewah kompak', 'image' => ''],
            ['brand' => 'Mercedes-Benz', 'model' => 'GLA', 'year' => 2019, 'description' => 'SUV premium kompak', 'image' => ''],
            ['brand' => 'Honda', 'model' => 'Civic', 'year' => 2018, 'description' => 'Sedan sporty'],
            ['brand' => 'Suzuki', 'model' => 'Ertiga', 'year' => 2019, 'description' => 'MPV hemat bahan bakar'],
            ['brand' => 'Mitsubishi', 'model' => 'Pajero Sport', 'year' => 2020, 'description' => 'SUV tangguh'],
            ['brand' => 'Daihatsu', 'model' => 'Xenia', 'year' => 2016, 'description' => 'Mobil keluarga ekonomis'],
            ['brand' => 'Nissan', 'model' => 'X-Trail', 'year' => 2017, 'description' => 'SUV dengan performa tinggi'],
            ['brand' => 'Mazda', 'model' => 'CX-5', 'year' => 2021, 'description' => 'SUV modern dan mewah'],
            ['brand' => 'Hyundai', 'model' => 'Creta', 'year' => 2022, 'description' => 'SUV kompak elegan'],
            ['brand' => 'Kia', 'model' => 'Rio', 'year' => 2019, 'description' => 'Hatchback ekonomis'],
            ['brand' => 'Wuling', 'model' => 'Confero', 'year' => 2020, 'description' => 'MPV keluarga baru'],
            ['brand' => 'Isuzu', 'model' => 'Panther', 'year' => 2013, 'description' => 'Diesel tangguh'],
            ['brand' => 'Ford', 'model' => 'Ranger', 'year' => 2018, 'description' => 'Pickup kuat dan tangguh'],
            ['brand' => 'Chevrolet', 'model' => 'Trailblazer', 'year' => 2019, 'description' => 'SUV Amerika kokoh'],
            ['brand' => 'BMW', 'model' => '320i', 'year' => 2021, 'description' => 'Sedan mewah premium'],
            ['brand' => 'Mercedes-Benz', 'model' => 'C200', 'year' => 2021, 'description' => 'Luxury sedan elegan'],
            ['brand' => 'Peugeot', 'model' => '3008', 'year' => 2022, 'description' => 'SUV Eropa bergaya futuristik'],
            ['brand' => 'Lexus', 'model' => 'RX300', 'year' => 2023, 'description' => 'SUV premium hybrid'],
            ['brand' => 'Subaru', 'model' => 'Forester', 'year' => 2020, 'description' => 'SUV AWD dengan performa tinggi'],
            ['brand' => 'Tesla', 'model' => 'Model 3', 'year' => 2022, 'description' => 'Sedan listrik modern'],
            ['brand' => 'MG', 'model' => 'ZS EV', 'year' => 2023, 'description' => 'SUV listrik masa depan'],
        ];

        foreach ($mobils as $m) {
            ModelMobil::create($m);
        }
    }
}
