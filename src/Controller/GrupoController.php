<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\GrupoRepository;
use Symfony\Component\HttpFoundation\Request;

final class GrupoController extends AbstractController
{
    
    #[Route('/grupo/crear', name: 'app_grupo_crear')]
    public function crearGrupo(Request $request, GrupoRepository $repo){
        try{
            $datos = json_decode($request->getContent(), true);

            $repo->crearGrupo($datos);
            return $this->json(['message' => 'Grupo creado correctamente']);
        }catch(\Exception $e){
            return $this->json(['message' => 'Error al crear el grupo']);
        }

    }
    #[Route('/grupo/ver', name: 'app_grupo_ver')]
    public function verGrupo(Request $request, GrupoRepository $repo){
        try{
            $datos = json_decode($request->getContent(), true);

            $grupo=$repo->verGrupo();
            return $this->json($grupo);
        }catch(\Exception $e){
            return $this->json(['message' => 'Error al mostrar el grupo']);
        }
    }

    #[Route('/grupo/actualizar', name: 'app_grupo_actualizar')]
    public function actualizarNombreGrupo(Request $request, GrupoRepository $repo){
        try{
            $datos = json_decode($request->getContent(), true);

            $repo->actualizarNombreGrupo($datos['id'],$datos['nombre_actualizado']);
            return $this->json(['message' => 'Grupo actualizado correctamente']);
        }catch(\Exception $e){
            return $this->json(['message' => 'Error al actualizar el grupo']);
        }
    }

    #[Route('/grupo/eliminar', name: 'app_grupo_eliminar')]
    public function eliminarGrupo(Request $request, GrupoRepository $repo){
        try{
            $datos = json_decode($request->getContent(), true);

            $repo->eliminarGrupo($datos['id']);
            return $this->json(['message' => 'Grupo eliminado correctamente']);
        }catch(\Exception $e){
            return $this->json(['message' => 'Error al eliminar el grupo']);
        }
    }
}
