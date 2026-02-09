<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Categoria;
use App\Enum\EstadoTarea;
use App\Enum\Prioridad;
use App\Repository\CategoriaRepository;
use Doctrine\ORM\Query\Expr\Func;

final class CategoriaController extends AbstractController
{
    #[Route('/api/categoria/crear', name: 'app_categoria_crear')]
    public function crearCategoria(Request $request,CategoriaRepository $repo):JsonResponse
    {
        try{
            //$datos = json_decode($request->getContent(),true);
            $datos=[
                "nombre_categoria" => 'Categoria de prueba a eliminar',
                "color" => 'Rojo'
            ];
            $repo->crearCategoria($datos);
            return $this->json(['message' => 'Categoria creada correctamente']);
        }catch(\Exception $e){
            return $this->json(['message' => 'Error al crear la categoria']);
        } 
    }

    #[Route('/api/categoria/eliminar', name: 'app_categoria_eliminar')]
    public function eliminarCategoria(Request $request,CategoriaRepository $repo):JsonResponse{
        try{
            $datos = json_decode($request->getContent(),true);
            $repo->eliminarCategoria($datos['id']);
            return $this->json(['message' => 'Categoria eliminada correctamente']);
        }catch(\Exception $e){
            return $this->json(['message' => 'Error al eliminar la categoria']);
        }
    }

    #[Route('/api/categoria/ver', name: 'app_categoria_ver')]
    public function verCategorias(CategoriaRepository $repo):JsonResponse{
        try{
            $categorias = $repo->verCategorias();
            return $this->json($categorias);
        }catch(\Exception $e){
            return $this->json(['message' => 'Error al mostrar las categorias']);
        }
    }
}
