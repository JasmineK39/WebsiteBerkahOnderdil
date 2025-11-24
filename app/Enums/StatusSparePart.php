<?php

namespace App\Enums;

// Ini adalah "Backed Enum", di mana setiap case memiliki nilai string
enum statussparepart: string
{
    case AVAILABLE = 'Available';
    case SOLD_OUT = 'Sold Out';
}