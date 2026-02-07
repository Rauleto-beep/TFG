<?php

namespace App\Controller;

use App\Repository\TareaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Tarea;
use App\Enum\EstadoTarea;
use App\Enum\Prioridad;
use Doctrine\ORM\Query\Expr\Func;

final class TareaController extends AbstractController
{
    //Crear tareas
    #[Route('/api/tarea/crear', name: 'app_tarea_crear')]
    public function crearTareas(Request $request, TareaRepository $repo): JsonResponse{
        $datos = json_decode($request->getContent(),true); //JSON que viene de Vue con los datos
        /*
        $datos = [
            "nombre_tarea" => "Prueba TFG tarea eliminar",
            "descripcion"=> "Descripción de prueba",
            "fecha_publicacion"=> "2026-02-07 12:00:00",
            "fecha_vencimiento"=> "2026-02-08 20:00:00",
            "ia"=> false,
            "estado"=> "No completada",
            "prioridad"=> "Alta",
            "categoria_id"=> null,
            "grupo_id"=> null
        ];
        */

        //Validaciones
        if (!isset($datos['nombre_tarea'])){
            return $this->json(['error' => 'Falta el nombre de la tarea'], 400);
        }else if (!isset($datos['fecha_vencimiento'])){
            return $this->json(['error' => 'Falta fecha de vencimiento'], 400);
        }
        $repo->crearTarea($datos);
    
        return $this->json([ 'message' => 'Tarea creada!']);
    }

    //Ver todas las tareas
    #[Route('/api/tarea/ver_todas', name: 'app_tarea_ver_todas')]
    public function verTareas(TareaRepository $repo):JsonResponse{
        $tareas = $repo->verTodasTareas();
        return $this->json($tareas);
    }

    #[Route('/api/tarea/ver_todas_estado', name: 'app_tarea_ver_todas_estado')]
    public function verTareasPorEstado(Request $request,TareaRepository $repo):JsonResponse{
        $datos = json_decode($request->getContent(),true);

        $tareas = $repo->verTodasTareas($datos['estado']);
        return $this->json($tareas);
    }

    #[Route('/api/tarea/ver_todas_prioridad', name: 'app_tarea_ver_todas_prioridad')]
    public function verTareasPorPrioridad(Request $request,TareaRepository $repo): JsonResponse{
        $datos = json_decode($request->getContent(),true);

        $tareas = $repo->verTodasTareas($datos['prioridad']);

        return $this->json($tareas);
    }

    #[Route('/api/tarea/ver_todas_categoria', name: 'app_tarea_ver_todas_categoria')]
    public function verTareasPorCategoria(Request $request,TareaRepository $repo):JsonResponse{
        $datos = json_decode($request->getContent(),true);

        $tareas = $repo->verTodasTareas($datos['categoria']);

        return $this->json($tareas);
    }

    #[Route('/api/tarea/ver_todas_fechaVencimiento', name: 'app_tarea_ver_todas_fechaVencimiento')]
    public function verTareasPorFechaVencimiento(Request $request,TareaRepository $repo):JsonResponse{
        $datos = json_decode($request->getContent(),true);

        $tareas = $repo->verTodasTareas($datos['fecha_vencimiento']);

        return $this->json($tareas);
    }

    //Eliminar tareas por id
    #[Route('/api/tarea/eliminar', name: 'app_tarea_eliminar')]
    public function eliminarTareas(Request $request,TareaRepository $repo):JsonResponse{
        $datos = json_decode($request->getContent(),true);

        $repo->eliminarTarea($datos['id']);
        return $this->json([ 'message' => 'Tarea eliminada correctamente!']);
    }

    //Actualizar tareas
    #[Route('/api/tarea/actualizar_nombre', name: 'app_tarea_actualizar_nombre')]
    public function actualizarNombreTarea(Request $request, TareaRepository $repo):JsonResponse{
        $datos = json_decode($request->getContent(), true);

        
        if (!isset($datos['id']) || !isset($datos['nombre_tarea'])) {
            return $this->json(['error' => 'Faltan datos'], 400);
        }

        $repo->actualizarNombreTarea($datos['id'], $datos['nombre_tarea']);

        return $this->json(['message' => 'Nombre actualizado correctamente']);
    }

    #[Route('/api/tarea/actualizar_estado', name: 'app_tarea_actualizar_estado')]
    public function actualizarEstadoTarea(Request $request, TareaRepository $repo):JsonResponse{
        $datos = json_decode($request->getContent(), true);

        
        if (!isset($datos['id']) || !isset($datos['estado'])) {
            return $this->json(['error' => 'Faltan datos'], 400);
        }

        $repo->actualizarEstadoTarea($datos['id'], $datos['estado']);

        return $this->json(['message' => 'Estado actualizado correctamente']);

    }

    #[Route('/api/tarea/actualizar_descripcion', name: 'app_tarea_actualizar_descripcion')]
    public function actualizarDescripcionTarea(Request $request, TareaRepository $repo):JsonResponse{
        $datos = json_decode($request->getContent(), true);


        if (!isset($datos['id']) || !isset($datos['descripcion'])) {
            return $this->json(['error' => 'Faltan datos'], 400);
        }

        $repo->actualizarDescripcionTarea($datos['id'], $datos['descripcion']);

        return $this->json(['message' => 'Descripcion actualizada correctamente']);

    }

    #[Route('/api/tarea/actualizar_categoria', name: 'app_tarea_actualizar_categoria')]
    public function actualizarCategoriaTarea(Request $request, TareaRepository $repo):JsonResponse{
        $datos = json_decode($request->getContent(), true);
        $datos['categoria_id'] = '1';
        $datos['id'] = 1;

        if (!isset($datos['id']) || !isset($datos['categoria_id'])) {
            return $this->json(['error' => 'Faltan datos'], 400);
        }

        $repo->actualizarCategoriaTarea($datos['id'], $datos['categoria_id']);

        return $this->json(['message' => 'Categoria actualizada correctamente']);
        
    }
    public function actualizarFechaVencimientoTarea(Request $request, TareaRepository $repo):JsonResponse{
        $datos = json_decode($request->getContent(), true);

        if (!isset($datos['id']) || !isset($datos['fecha_vencimiento'])) {
            return $this->json(['error' => 'Faltan datos'], 400);
        }

        try {
            $repo->actualizarFechaVencimiento($datos['id'], $datos['fecha_vencimiento']);
            return $this->json(['message' => 'Fecha de vencimiento actualizada']);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Formato de fecha no válido'], 400);
        }
        
    }
}
