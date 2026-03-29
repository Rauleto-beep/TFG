<?php

namespace App\Repository;

use App\Entity\Grupo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Grupo>
 */
class GrupoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grupo::class);
    }

    //Formateo de fecha distinto para el FRONT
    private function ejecutarYFormatear(string $sql, array $params = []): array {
        $resultados = $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetchAllAssociative();

        foreach ($resultados as &$fila) {
            if (isset($fila['fecha_creacion']) && $fila['fecha_creacion']) {
                $fila['fecha_creacion'] = (new \DateTime($fila['fecha_creacion']))->format('d-m-Y H:i:s');
            }
        }
        return $resultados;
    }

    
    public function crearGrupo(array $datos){
        $sql = "INSERT INTO grupo (nombre_grupo,fecha_creacion) 
                VALUES (:nombre, :fecha)";

        $params =[
            "nombre" => $datos['nombre_grupo'],
            "fecha" => $datos['fecha_creacion']
        ];
        $this->getEntityManager()->getConnection()->executeQuery($sql, $params);

        $sqlGrupoUsuario = "INSERT INTO grupo_usuario (grupo_id,usuario_id)  VALUES  (:grupo,:usuario)";
        $grupoId = $this->getEntityManager()->getConnection()->lastInsertId();
        $paramGrupoUsuario =[
            "grupo" => $grupoId,
            "usuario" => $datos['usuario_id']
        ];
        $this->getEntityManager()->getConnection()->executeQuery($sqlGrupoUsuario, $paramGrupoUsuario);
    }

    public function eliminarGrupo(int $id){
        $sql = "DELETE FROM grupo WHERE id = :id";
        $sqlGrupoUsuario = "DELETE FROM grupo_usuario WHERE grupo_id = :id";
        $params = [
            'id' => $id
        ];
        
        $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
        $this->getEntityManager()->getConnection()->executeQuery($sqlGrupoUsuario, $params);
    }

    public function actualizarNombreGrupo(int $id, string $nuevoNombre){
        $sql = "UPDATE grupo SET nombre_grupo = :nombre WHERE id = :id";
    
        $params = [
            'nombre' => $nuevoNombre,
            'id'     => $id
        ];
        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }

    public function verGrupo(int $usuarioId){
        $sql = "SELECT g.id, g.nombre_grupo, g.fecha_creacion  FROM grupo g
                INNER JOIN grupo_usuario gu ON g.id = gu.grupo_id WHERE gu.usuario_id = :id;";

        $params = [
            'id' => $usuarioId
        ];
        // Aquí usamos el formateador visual
        return $this->ejecutarYFormatear($sql,$params);
    }

    public function crarGrupo(array $datos){
        // 1. Preparamos la fecha para la BBDD (Formato ISO)
        $fechaParaBBDD = (new \DateTime($datos['fecha_creacion'] ?? 'now'))->format('Y-m-d H:i:s');

        $sql = "INSERT INTO grupo (nombre_grupo, fecha_creacion) VALUES (:nombre, :fecha)";
        $params = [
            "nombre" => $datos['nombre_grupo'],
            "fecha"  => $fechaParaBBDD
        ];
        
        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }
}
