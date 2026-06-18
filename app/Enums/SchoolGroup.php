<?php

namespace App\Enums;

use ParseError;

enum SchoolGroup: string
{
    case SK1 = 'sekolah-kemurnian-1';
    case SK2 = 'sekolah-kemurnian-2';
    case SK3 = 'sekolah-kemurnian-3';

    public function label(): string
    {
        return match ($this) {
            self::SK1 => 'Sekolah Kemurnian 1',
            self::SK2 => 'Sekolah Kemurnian 2',
            self::SK3 => 'Sekolah Kemurnian 3',
        };
    }
}
