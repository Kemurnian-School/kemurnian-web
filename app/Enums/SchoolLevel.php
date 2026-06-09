<?php

namespace App\Enums;

enum SchoolLevel: string
{
    case TK = 'tk';
    case SD = 'sd';
    case SMP = 'smp';
    case SMA = 'sma';

    public function label(): string
    {
        return strtoupper($this->value);
    }
}
