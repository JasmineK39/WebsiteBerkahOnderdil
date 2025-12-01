<?php

namespace App\Enums;

// Ini adalah "Backed Enum", di mana setiap case memiliki nilai string
enum grade: string
{
    case A = 'A';
    case B = 'B';
    case C = 'C';
}