<?php

namespace App\Controller;

use App\Repository\MensajeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class MensajeController extends AbstractController
{
    #[Route('api/mensaje/ver', name: 'app_mensaje_ver')]
    public function verMensaje(MensajeRepository $repo):JsonResponse{
        try{
            $mensajes = $repo->verMensaje();
            return $this->json($mensajes);
        }catch(\Exception $e){
            return $this->json([ 'message' => 'Error al mostrar los mensajes']);
        }
    }

    #[Route('api/mensaje/crear', name: 'app_mensaje_crear')]
    public function crearMensaje(Request $request,MensajeRepository $repo):JsonResponse{
        try{
            $datos = json_decode($request->getContent(),true);
            $repo->crearMensaje($datos);
            if (!isset($datos['contenido'])){
                return $this->json([ 'message' => 'No puede estar vacio el mensaje']);
            }else{
                return $this->json([ 'message' => 'Mensaje creado']);
            }
        }catch(\Exception $e){
            return $this->json([ 'message' => 'Error al crear el mensaje']);
        } 
    }

    #[Route('api/mensaje/eliminar', name: 'app_mensaje_eliminar')]
    public function eliminarMensaje(Request $request,MensajeRepository $repo):JsonResponse{
        try{
            $datos = json_decode($request->getContent(),true);
            $repo->eliminarMensaje($datos['id']);
            if (!isset($datos['id'])){
                return $this->json([ 'message' => 'Selecciona el mensaje a eliminar']);
            }else{
                return $this->json([ 'message' => 'Mensaje eliminado']);
            }
        }catch(\Exception $e){
            return $this->json([ 'message' => 'Error al eliminar el mensaje']);
        } 
    }
}
