<?php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

/**
 * @extends ServiceEntityRepository<Usuario>
 */
class UsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usuario::class);
    }

    //Creo el registro en la tabla pero lo dejo en pendiente y no se mostrara hasta que sea aceptado
    public function enviarSolicitud(int $idDe, int $idPara)
    {
        $sql = "INSERT INTO contactos (usuario_id_1, usuario_id_2, estado) 
            VALUES (:u1, :u2, 'pendiente')";
        $params=[
            'u1' => $idDe,
            'u2' => $idPara
        ];
        return $this->getEntityManager()->getConnection()->executeQuery($sql,$params);
    }

    //Cuando el usuario 2 acepte la solicitud,se ejecutara este metodo que la actualiza
    public function aceptarSolicitud(int $idDe, int $idPara){
        $sql = "UPDATE contacto SET estado = 'aceptado' 
            WHERE usuario_solicitante_id = :u1 AND usuario_receptor_id = :u2";

        $params=[
            'u1' => $idDe,
            'u2' => $idPara
        ];
        return $this->getEntityManager()->getConnection()->executeQuery($sql,$params);
    }

    //Metodo para listar los contactos de 1 usuario
    public function listarAmigos(int $idUsuario){
        $sql="SELECT u.id, u.nombre_usuario 
              FROM usuario u
              JOIN contacto c ON (u.id = c.usuario_id_1 OR u.id = c.usuario_id_2)
              WHERE (c.usuario_id_1 = :id OR c.usuario_id_2 = :id)
              AND u.id != :id
              AND c.estado = 'aceptado'";
              
        return $this->getEntityManager()->getConnection()->executeQuery($sql, ['id' => $idUsuario])->fetchAllAssociative();
    }

    //Crear usuario
    public function crearUsuario(array $datos,UserPasswordHasherInterface $hasher){
        $user = new Usuario(); 
    
        $passwordHasheada = $hasher->hashPassword($user, $datos['password']);

        $sql = "INSERT INTO usuario (nombre_usuario,correo,password_hash,fecha_creacion) 
                VALUES (:nombre,:correo,:password_hash ,:fecha_creacion)";

        $params=[
            "nombre" => $datos['nombre_usuario'],
            "correo" => $datos['correo'],
            "password_hash" => $passwordHasheada,
            "fecha_creacion" => date('Y-m-d')
        ];
        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }

    //Eliminar usuario
    public function eliminarUsuario(int $id){
        $sql = "DELETE FROM usuario WHERE id = :id";
        $params=[
            "id" => $id
        ];
        return $this->getEntityManager()->getConnection()->executeQuery($sql,$params);
    }

    //Actualizar usuario
    public function actualizarUsuario(array $datos){
        $sql = "UPDATE usuario SET nombre_usuario = :usuario, correo = :correo,password_hash = :password
                WHERE usuario_id = :id";
        $params=[
            "usuario" => $datos['nombre_usuario'],
            "correo" => $datos['correo'],
            "password" => $datos['password_hash']
        ];
        return $this->getEntityManager()->getConnection()->executeQuery($sql,$params);
    }

    //Ver usuario
    public function verUsuario(){
        $sql = "SELECT id,nombre_usuario,correo,fecha_creacion FROM usuario";
        return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAllAssociative();
    }
}
