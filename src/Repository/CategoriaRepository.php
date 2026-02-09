<?php

namespace App\Repository;

use App\Entity\Categoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categoria>
 */
class CategoriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categoria::class);
    }

    public function crearCategoria(array $datos){
        $sql="INSERT INTO categoria (nombre_categoria, color) 
            VALUES (:nombre, :color)";
        $params=[
            "nombre" => $datos['nombre_categoria'],
            "color" => $datos['color']
        ];
        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }

    public function eliminarCategoria(int $id){
        $sql = "DELETE FROM categoria WHERE id = :id";
        $params=[
            "id" => $id
        ];
        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }

    public function verCategorias(){
        $sql = "SELECT id, nombre_categoria, color FROM categoria";
        return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAllAssociative();
    }

}
