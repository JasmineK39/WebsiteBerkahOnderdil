<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $kategoris = [
            ['name' => 'Rem', 'description' => 'Kampas, cakram, tromol'],
            ['name' => 'Mesin', 'description' => 'Filter oli, busi, piston'],
            ['name' => 'Transmisi', 'description' => 'Kopling, gear, oli transmisi'],
            ['name' => 'Suspensi', 'description' => 'Shockbreaker, per, arm'],
            ['name' => 'Kelistrikan', 'description' => 'Aki, lampu, kabel, relay'],
        ];

        foreach ($kategoris as $k) {
            Kategori::create($k);
        }
    }
}
