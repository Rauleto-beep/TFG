<?php

namespace App\Enum;


enum EstadoTarea: string
{
    case PENDIENTE = 'Pendiente';
    case ACEPTADA = 'Aceptada';
}