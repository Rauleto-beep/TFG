<?php

namespace App\Repository;

use App\Entity\Mensaje;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mensaje>
 */
class MensajeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mensaje::class);
    }
    private function ejecutarYFormatear(string $sql): array{
    $resultados = $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAllAssociative();

    foreach ($resultados as &$tarea) {
        
        if ($tarea['fecha_envio']) {
            $tarea['fecha_envio'] = (new \DateTime($tarea['fecha_envio']))->format('d-m-Y H:i:s');
        }
    }

    return $resultados;
    }

    public function verMensaje(int $id){
        $sql = "SELECT m.contenido as mensaje, m.fecha_envio, u.nombre_usuario as autor, m.autor_id 
                FROM mensaje m JOIN usuario u ON m.autor_id = u.id WHERE m.grupo_id = :grupo_id 
                ORDER BY m.fecha_envio ASC";
        $params = [
            "grupo_id" => $id
        ];
        return $this->getEntityManager()->getConnection()->executeQuery($sql,$params)->fetchAllAssociative();

    }

    public function crearMensaje(array $datos){
        $sql="INSERT INTO mensaje (contenido,fecha_envio,grupo_id,autor_id) 
              VALUES (:contenido,:fEnv,:grupo_id,:autor_id)";
        $params=[
            "contenido" => $datos['contenido'],
            "fEnv" => $datos['fecha_envio'],
            "grupo_id" => $datos['grupo_id'],
            "autor_id" => $datos['autor_id']
        ];

        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }

    public function eliminarMensaje(int $id){
        $sql="DELETE FROM mensaje WHERE id = :id";
        $params=[
            "id" => $id
        ];
        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }
}
