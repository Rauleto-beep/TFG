<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use App\Repository\MensajeRepository;
use App\Entity\Usuario;

// src/Controller/ChatController.php
final class ChatController extends AbstractController{
    #[Route('/api/chat/enviar', name: 'app_chat_enviar', methods: ['POST'])]
        public function enviar(Request $request, HubInterface $hub, MensajeRepository $mrepo): JsonResponse {
            $datos = json_decode($request->getContent(), true);
            $usuario = $this->getUser(); // Tu sistema JWT ya identifica al usuario
            
            //Guardar en base de datos el mensaje
            $mensaje = [
                "contenido" => $datos["contenido"],
                "fecha_envio" => $datos["fecha_envio"],
                "grupo_id" => $datos["grupo_id"],
                "autor_id" => $datos["autor_id"]
            ];

            $mrepo->crearMensaje($mensaje);
            $topic = "https://localhost:8081/chat/grupo/" . $datos["grupo_id"];
            // Publicar en Mercure
            $update = new Update(
                $topic, // El "tópico" o canal
                json_encode([
                    'mensaje' => $datos["contenido"],
                    'autor' => $usuario->getNombreUsuario(),
                    'autor_id' => $datos["autor_id"],
                    'grupo_id' => $datos["grupo_id"]
                ]),
                false
    );
    $hub->publish($update);

    return $this->json(['status' => 'Mensaje enviado']);
    }

    #[Route('/api/chat/ver_historial/{grupoId}', name: 'app_chat_ver', methods: ['GET'])]
    public function historial(int $grupoId, MensajeRepository $mrepo): JsonResponse {
        $mensajes = $mrepo->verMensaje($grupoId);
        return $this->json($mensajes);
    }
}