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

=======
>>>>>>> 6ed2205f85b9826a31eae9b670fca7c4b7ec218c
        ];

        foreach ($mobils as $m) {
            ModelMobil::create($m);
        }
    }
}
