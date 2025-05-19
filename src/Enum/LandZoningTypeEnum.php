<?php

namespace App\Enum;

enum LandZoningTypeEnum: string
{
    case AGRICULTURAL = 'agricultural';
    case FORESTRY = 'forestry';
    case INDUSTRIAL = 'industrial';
    case REGULATED = 'regulated';
    case UNREGULATED = 'unregulated';

}
