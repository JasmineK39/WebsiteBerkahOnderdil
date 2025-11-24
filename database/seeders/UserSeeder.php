<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(['name'=>'admin','email' =>'admin@gmail.com', 'status'=>'active','role'=>'admin','phone'=>'081326553304','password'=>Hash::make('admin123'),]);
        User::create(['name'=>'test','email' =>'test@gmail.com', 'status'=>'active','role'=>'customer','phone'=>'081234567890','password'=>Hash::make('testcust123'),]);
    }
}
