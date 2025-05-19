<?php

namespace App\Enum;

enum CommercialTypeEnum:string
{
    case OFFICE = 'office';
    case RETAIL = 'retail';
    case WAREHOUSE = 'warehouse';
    case INDUSTRIAL = 'industrial';
}
