<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UsuarioRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UsuarioController extends AbstractController
{
    #[Route('/usuario/aceptar_solicitud', name: 'app_usuario_aceptar_solicitud')]
    public function aceptar(Request $request, UsuarioRepository $repo): JsonResponse{
        try{
            $datos = json_decode($request->getContent(), true);
    
            // Validamos que vengan los IDs necesarios
            if (!isset($datos['id_solicitante']) || !isset($datos['id_receptor'])) {
                return $this->json(['error' => 'Datos insuficientes'], 400);
            }

            $repo->aceptarSolicitud($datos['id_solicitante'], $datos['id_receptor']);

            return $this->json(['message' => '¡Solicitud aceptada!']);
        }catch(\Exception $e){
            return $this->json(['message' => 'Error al aceptar la solicitud']);
        }
    }

    #[Route('/usuario/crear', name: 'app_usuario_crear')]
    public function crearUsuario(Request $request, UsuarioRepository $repo,UserPasswordHasherInterface $hasher):JsonResponse{
        try{
            $datos = json_decode($request->getContent(), true);

            $repo->crearUsuario($datos, $hasher);
            return $this->json(['message' => 'Usuario creado correctamente']);
        }catch(\Exception $e){
            return $this->json(['message' => 'Error al crear usuario']);
        }
        
    }

    #[Route('/api/comprobar-usuario', name: 'api_check', methods: ['POST'])]
public function verificar(Request $request, UsuarioRepository $repo, UserPasswordHasherInterface $hasher): JsonResponse 
{
    $data = json_decode($request->getContent(), true);
    
    // Si el JSON está mal formado o falta el correo
    if (!$data || !isset($data['correo'])) {
        return $this->json(['message' => 'Datos incompletos'], 400);
    }

    $user = $repo->findOneBy(['correo' => $data['correo']]);

    if (!$user) {
        return $this->json([
            'status' => 'error',
            'message' => 'Este correo electrónico no está registrado.'
        ], 404);
    }

    // Si también enviamos la contraseña (para verificar credenciales)
    if (isset($data['password']) && !empty($data['password'])) {
        if (!$hasher->isPasswordValid($user, $data['password'])) {
            return $this->json([
                'status' => 'error',
                'message' => 'La contraseña es incorrecta.'
            ], 401);
        }
    }

    return $this->json([
        'status' => 'success',
        'message' => 'Usuario encontrado.'
    ], 200);
}

    #[Route('/usuario/actualizar', name: 'app_usuario_actualizar')]
    public function actualizarUsuario(Request $request, UsuarioRepository $repo):JsonResponse{
        try{
            $datos = json_decode($request->getContent(), true);

            $repo->actualizarUsuario($datos);
            return $this->json(['message' => 'Usuario actualizado correctamente']);
        }catch(\Exception $e){
            return $this->json(['message' => 'Error al actualizar el usuario']);
        }

    }

    #[Route('/usuario/eliminar', name: 'app_usuario_eliminar')]
    public function eliminarUsuario(Request $request, UsuarioRepository $repo):JsonResponse{
        try{
            $datos = json_decode($request->getContent(), true);

            $repo->eliminarUsuario($datos['id']);
            return $this->json(['message' => 'Usuario eliminado correctamente']);
        }catch(\Exception $e){
            return $this->json(['message' => 'Error al actualizar el usuario']);
        }
    }

    #[Route('/usuario/ver', name: 'app_usuario_ver')]
    public function verUsuario(Request $request, UsuarioRepository $repo):JsonResponse{
        try{
            $datos = json_decode($request->getContent(), true);

            $usuarios = $repo->verUsuario($datos);
            return $this->json($usuarios);
        }catch(\Exception $e){
            return $this->json(['message' => 'Error al mostrar la informacion del usuario']);
        }
    }
}
