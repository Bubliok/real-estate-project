<?php

namespace App\Enum;

enum ResidentialBuildTypesEnum: string
{
    case BRICK = 'brick';
    case PANEL = 'panel';
    case MONOLITHIC = 'monolithic';
    case PREFABRICATED = 'prefabricated';
}
