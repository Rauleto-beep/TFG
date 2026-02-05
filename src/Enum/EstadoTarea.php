<?php

namespace App\Enum;


enum EstadoTarea: string
{
    case NOCOMPLETADA = 'No completada';
    case COMPLETADA = 'Completada';
}