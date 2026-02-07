<?php

namespace App\Enum;


enum EstadoSolicitud: string
{
    case PENDIENTE = 'Pendiente';
    case ACEPTADA = 'Aceptada';
}