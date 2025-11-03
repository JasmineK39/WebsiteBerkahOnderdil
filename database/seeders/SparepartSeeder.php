<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModelMobil;
use App\Models\Kategori;
use App\Models\Sparepart;

class SparepartSeeder extends Seeder
{
    public function run()
    {
        $spareparts = [
            ['name' => 'Kampas Rem Depan', 'brand' => 'Brembo', 'type' => 'Rem', 'grade' => 'A', 'price' => 450000, 'image' => ''],
            ['name' => 'Kampas Rem Belakang', 'brand' => 'Brembo', 'type' => 'Rem', 'grade' => 'B', 'price' => 350000],
            ['name' => 'Rotor Rem', 'brand' => 'Brembo', 'type' => 'Rem', 'grade' => 'A', 'price' => 750000],
            ['name' => 'Minyak Rem DOT4', 'brand' => 'Prestone', 'type' => 'Rem', 'grade' => 'C', 'price' => 95000],
            ['name' => 'Kaliper Rem', 'brand' => 'TRW', 'type' => 'Rem', 'grade' => 'A', 'price' => 600000],

            ['name' => 'Filter Oli', 'brand' => 'Denso', 'type' => 'Mesin', 'grade' => 'A', 'price' => 75000],
            ['name' => 'Busi Iridium', 'brand' => 'NGK', 'type' => 'Mesin', 'grade' => 'C', 'price' => 120000],
            ['name' => 'Radiator', 'brand' => 'Denso', 'type' => 'Mesin', 'grade' => 'A', 'price' => 1200000],
            ['name' => 'Thermostat', 'brand' => 'Aisin', 'type' => 'Mesin', 'grade' => 'B', 'price' => 250000],

            ['name' => 'Kopling Set', 'brand' => 'Exedy', 'type' => 'Transmisi', 'grade' => 'A', 'price' => 950000],
            ['name' => 'Oli Transmisi', 'brand' => 'Shell', 'type' => 'Transmisi', 'grade' => 'B', 'price' => 185000],

            ['name' => 'Shockbreaker Depan', 'brand' => 'KYB', 'type' => 'Suspensi', 'grade' => 'C', 'price' => 850000],
            ['name' => 'Bushing Arm', 'brand' => '555', 'type' => 'Suspensi', 'grade' => 'A', 'price' => 220000],

            ['name' => 'Baterai Aki', 'brand' => 'GS Astra', 'type' => 'Kelistrikan', 'grade' => 'A', 'price' => 950000],
            ['name' => 'Lampu Depan LED', 'brand' => 'Philips', 'type' => 'Kelistrikan', 'grade' => 'C', 'price' => 450000],
        ];

        $mobils = ModelMobil::all();
        $kategoris = Kategori::all();

        foreach ($spareparts as $item) {
            $mobil = $mobils->random(); // ambil mobil acak
            $kategori = $kategoris->firstWhere('name', $item['type']);

            $sp = Sparepart::create([
                'model_mobil_id' => $mobil->id,
                'name' => $item['name'],
                'brand' => $item['brand'],
                'type' => $item['type'],
                'grade' => $item['grade'],
                'stock' => rand(5, 50),
                'price' => $item['price'],
                'description' => "{$item['name']} untuk {$mobil->brand} {$mobil->model}",
                'status' => 'available',
            ]);

            if ($kategori) {
                $sp->kategoris()->attach($kategori->id);
            }
        }
    }
}
