<?php

namespace App\EventListener;

use App\Entity\Usuario;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Cookie;

class JWTCreatedListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        // Verificamos que el usuario existe y es de nuestra clase
        if (!$user instanceof \App\Entity\Usuario) {
            return;
        }

        // 1. Mantienes tu lógica actual: información extra al JSON
        $data['user'] = [
            'id' => $user->getId(),
            'correo' => $user->getCorreo(),
            'nombre' => $user->getNombreUsuario(),
        ];
        $event->setData($data);

        // 2. NUEVO: Inyectamos la Cookie para Mercure
        $response = $event->getResponse();
        
        // Creamos la cookie con el mismo Token JWT que Lexik ya generó
        $response->headers->setCookie(
            Cookie::create('mercureAuthorization')
                ->withValue($data['token']) // El token JWT de Lexik sirve para Mercure si la clave es la misma
                ->withPath('/')             // Disponible en toda la web
                ->withDomain(null)          // Localhost
                ->withSecure(true)          // Obligatorio para HTTPS (Fase 1)
                ->withHttpOnly(false)       // Permitimos que JS la vea si es necesario
                ->withSameSite('Lax')
        );
    }
}