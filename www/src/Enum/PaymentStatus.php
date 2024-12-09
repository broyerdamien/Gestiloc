<?php

namespace App\Enum;

enum PaymentStatus: string
{
    case EN_ATTENTE = 'en attente';
    case PARTIEL = 'partiel';
    case PAYE = 'paye';
}
