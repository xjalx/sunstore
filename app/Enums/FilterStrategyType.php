<?php

namespace App\Enums;

enum FilterStrategyType: string
{
    case RANGE = 'range';
    case EXACT = 'exact';
}
