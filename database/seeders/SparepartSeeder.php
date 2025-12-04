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
