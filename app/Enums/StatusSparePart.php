<?php

namespace App\Enums;

// Ini adalah "Backed Enum", di mana setiap case memiliki nilai string
enum statussparepart: string
{
    case available = 'available';
    case sold_out = 'sold_out';
}