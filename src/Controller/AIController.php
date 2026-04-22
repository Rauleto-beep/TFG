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
use App\Repository\UsuarioRepository;
use Doctrine\ORM\Query\Expr\Func;

final class AIController extends AbstractController
{
    #[Route('/api/ai/crear', methods: ['POST'], name: 'app_crear_ia')]
public function crearTareaIA(Request $request, TareaRepository $repo,UsuarioRepository $userRepo): JsonResponse
{
    $user = $this->getUser();
    if (!$user) {
        $user = $userRepo->find(1); // Suponiendo que tu ID es 1
    }
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
public function actualizarEstadoTarea(Request $request, TareaRepository $repo,UsuarioRepository $userRepo): JsonResponse {
    try {
        $datos = json_decode($request->getContent(), true);
        $nombre = $datos['nombre_tarea'];
        $estado = 'Completada';

        $user = $this->getUser();
        if (!$user) {
            $user = $userRepo->find(1); // Suponiendo que tu ID es 1
        }

        $repo->actualizarEstadoTareaIA($nombre, $estado);
        return $this->json(['message' => 'Estado actualizado correctamente']);
    } catch (\Exception $e) {
        return $this->json(['message' => 'Error: ' . $e->getMessage()], 500);
    }
}

#[Route('/api/ai/tarea/eliminar', methods: ['POST'], name: 'app_tarea_eliminar_ia')]
public function eliminarTarea(Request $request, TareaRepository $repo,UsuarioRepository $userRepo): JsonResponse {
    try {
        $datos = json_decode($request->getContent(), true);
        $user = $this->getUser();
        if (!$user) {
            $user = $userRepo->find(1); // Suponiendo que tu ID es 1
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
public function editarTareaIA(Request $request, TareaRepository $repo,UsuarioRepository $userRepo): JsonResponse
{
    $user = $this->getUser();
    if (!$user) {
        $user = $userRepo->find(1); // Suponiendo que tu ID es 1
    }
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

//Listar tareas IA
#[Route('/api/ai/tarea/listar', methods: ['GET'])]
public function listarTareasIA(TareaRepository $repo, UsuarioRepository $userRepo): JsonResponse
{
    $user = $this->getUser();

    if (!$user) {
        $user = $userRepo->find(1); // Suponiendo que tu ID es 1
    }

    try {
        // Obtenemos el array de tareas desde el repositorio
        $tareas = $repo->listarTareasIA($user->getId()); 
        
        // Devolvemos directamente el array de tareas
        return $this->json(['tareas' => $tareas]);
    } catch (\Exception $e) {
        return $this->json(['message' => 'Error al obtener tareas: ' . $e->getMessage()], 500);
    }
}
}

