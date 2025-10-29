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
        // ==== 1. Data Mobil ====
        $mobils = [
            ['brand' => 'Toyota', 'model' => 'Kijang Innova', 'year' => 2015, 'description' => 'MPV keluarga'],
            ['brand' => 'Honda', 'model' => 'Civic', 'year' => 2018, 'description' => 'Sedan sporty'],
            ['brand' => 'Suzuki', 'model' => 'Ertiga', 'year' => 2019, 'description' => 'MPV hemat bahan bakar'],
            ['brand' => 'Mitsubishi', 'model' => 'Pajero Sport', 'year' => 2020, 'description' => 'SUV tangguh'],
            ['brand' => 'Daihatsu', 'model' => 'Xenia', 'year' => 2016, 'description' => 'Mobil keluarga ekonomis'],
        ];

        $mobilRecords = [];
        foreach ($mobils as $m) {
            $mobilRecords[] = ModelMobil::create($m);
        }

        // ==== 2. Data Kategori ====
        $kategoris = [
            ['name' => 'Rem', 'description' => 'Kampas, cakram, tromol'],
            ['name' => 'Mesin', 'description' => 'Filter oli, busi, piston'],
            ['name' => 'Transmisi', 'description' => 'Kopling, gear, oli transmisi'],
            ['name' => 'Suspensi', 'description' => 'Shockbreaker, per, arm'],
            ['name' => 'Kelistrikan', 'description' => 'Aki, lampu, kabel, relay'],
        ];

        $kategoriRecords = [];
        foreach ($kategoris as $k) {
            $kategoriRecords[] = Kategori::create($k);
        }

        // ==== 3. Data Spareparts (20 items) ====
        $spareparts = [
            ['name' => 'Kampas Rem Depan', 'brand' => 'Brembo', 'type' => 'Rem', 'grade' => 'A', 'price' => 450000],
            ['name' => 'Kampas Rem Belakang', 'brand' => 'Brembo', 'type' => 'Rem', 'grade' => 'B', 'price' => 350000],
            ['name' => 'Filter Oli', 'brand' => 'Denso', 'type' => 'Mesin', 'grade' => 'A', 'price' => 75000],
            ['name' => 'Filter Udara', 'brand' => 'Toyota Genuine Parts', 'type' => 'Mesin', 'grade' => 'A', 'price' => 95000],
            ['name' => 'Busi Iridium', 'brand' => 'NGK', 'type' => 'Mesin', 'grade' => 'A', 'price' => 120000],
            ['name' => 'Kopling Set', 'brand' => 'Exedy', 'type' => 'Transmisi', 'grade' => 'A', 'price' => 950000],
            ['name' => 'Gearbox Manual', 'brand' => 'OEM', 'type' => 'Transmisi', 'grade' => 'B', 'price' => 3200000],
            ['name' => 'Oli Transmisi', 'brand' => 'Shell', 'type' => 'Transmisi', 'grade' => 'A', 'price' => 185000],
            ['name' => 'Shockbreaker Depan', 'brand' => 'KYB', 'type' => 'Suspensi', 'grade' => 'A', 'price' => 850000],
            ['name' => 'Per Belakang', 'brand' => 'Eibach', 'type' => 'Suspensi', 'grade' => 'A', 'price' => 650000],
            ['name' => 'Arm Lower', 'brand' => '555', 'type' => 'Suspensi', 'grade' => 'B', 'price' => 300000],
            ['name' => 'Baterai Aki', 'brand' => 'GS Astra', 'type' => 'Kelistrikan', 'grade' => 'A', 'price' => 950000],
            ['name' => 'Lampu Depan LED', 'brand' => 'Philips', 'type' => 'Kelistrikan', 'grade' => 'A', 'price' => 450000],
            ['name' => 'Relay Starter', 'brand' => 'Bosch', 'type' => 'Kelistrikan', 'grade' => 'B', 'price' => 120000],
            ['name' => 'Alternator', 'brand' => 'Denso', 'type' => 'Kelistrikan', 'grade' => 'A', 'price' => 1250000],
            ['name' => 'Kampas Kopling', 'brand' => 'Sakura', 'type' => 'Transmisi', 'grade' => 'B', 'price' => 550000],
            ['name' => 'Rotor Rem', 'brand' => 'Brembo', 'type' => 'Rem', 'grade' => 'A', 'price' => 750000],
            ['name' => 'Piston Kit', 'brand' => 'Art', 'type' => 'Mesin', 'grade' => 'A', 'price' => 1450000],
            ['name' => 'Fuel Pump', 'brand' => 'Walbro', 'type' => 'Mesin', 'grade' => 'A', 'price' => 950000],
            ['name' => 'Shockbreaker Belakang', 'brand' => 'KYB', 'type' => 'Suspensi', 'grade' => 'A', 'price' => 820000],
        ];

        foreach ($spareparts as $item) {
            $mobil = $mobilRecords[array_rand($mobilRecords)];
            $kategori = collect($kategoriRecords)->firstWhere('name', $item['type']);

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
