<?php

namespace App\EventListener;

use App\Entity\Usuario;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTCreatedListener{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
{
    $data = $event->getData();
    $user = $event->getUser();

    // Verificamos que el usuario existe y es de nuestra clase
    if (!$user instanceof \App\Entity\Usuario) {
        return;
    }

    // Añadimos la información extra al JSON de respuesta
    $data['user'] = [
        'id' => $user->getId(),
        'correo' => $user->getCorreo(),
        'nombre' => $user->getNombreUsuario(),
    ];

    $event->setData($data);
}
}