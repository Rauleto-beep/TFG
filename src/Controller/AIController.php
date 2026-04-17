<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Categoria;
use App\Enum\EstadoTarea;
use App\Enum\Prioridad;
use App\Repository\TareaRepository;
use Doctrine\ORM\Query\Expr\Func;

final class AIController extends AbstractController
{
    #[Route('/api/ai/crear', methods: ['POST'], name: 'app_crear_ia')]
public function crearTareaIA(Request $request, TareaRepository $repo): JsonResponse
{
    $user = $this->getUser();
    $datos = json_decode($request->getContent(), true);

    try {
        // Pasa el usuario actual para que la tarea se asigne a él
        $repo->crearTarea($datos, $user->getId()); 
        
        // Cambiamos 'title' por 'nombre_tarea'
        return $this->json(['message' => 'Tarea creada correctamente: ' . $datos['nombre_tarea']]);
    } catch (\Exception $e) {
        return $this->json(['message' => 'Error: ' . $e->getMessage()], 500);
    }
}

#[Route('/api/ai/tarea/actualizar_estado', methods: ['POST'], name: 'app_tarea_actualizar_estado_ia')]
public function actualizarEstadoTarea(Request $request, TareaRepository $repo): JsonResponse {
    try {
        $datos = json_decode($request->getContent(), true);
        $nombre = $datos['nombre_tarea'];
        $estado = 'Completada';

        $repo->actualizarEstadoTareaIA($nombre, $estado);
        return $this->json(['message' => 'Estado actualizado correctamente']);
    } catch (\Exception $e) {
        return $this->json(['message' => 'Error: ' . $e->getMessage()], 500);
    }
}

#[Route('/api/ai/tarea/eliminar', methods: ['POST'], name: 'app_tarea_eliminar_ia')]
public function eliminarTarea(Request $request, TareaRepository $repo): JsonResponse {
    try {
        $datos = json_decode($request->getContent(), true);
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['message' => 'Usuario no autenticado'], 401);
        }

        $exito = $repo->eliminarTareaIA($datos['nombre'], $user->getId());
        
        if ($exito) {
            return $this->json(['message' => 'Tarea eliminada correctamente']);
        } else {
            return $this->json(['message' => 'No se encontró la tarea o no tienes permiso'], 404);
        }
    } catch (\Exception $e) {
        return $this->json(['message' => 'Error: ' . $e->getMessage()], 500);
    }
}

#[Route('/api/ai/tarea/editar', methods: ['POST'], name: 'app_editar_ia')]
public function editarTareaIA(Request $request, TareaRepository $repo): JsonResponse
{
    $user = $this->getUser();
    $datos = json_decode($request->getContent(), true);

    try {
        // Pasa el usuario actual para que la tarea se asigne a él
        $repo->editarTareaIA($datos['nombre'],$datos['cambios'], $user->getId()); 
        
        // Cambiamos 'title' por 'nombre_tarea'
        return $this->json(['message' => 'Tarea creada correctamente: ' . $datos['nombre_tarea']]);
    } catch (\Exception $e) {
        return $this->json(['message' => 'Error: ' . $e->getMessage()], 500);
    }
}
}
