<?php

namespace App\Repository;

use App\Entity\Tarea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Enum\EstadoTarea;
use App\Enum\Prioridad;

/**
 * @extends ServiceEntityRepository<Tarea>
 */
class TareaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tarea::class);
    }
    private function ejecutarYFormatear(string $sql, array $params): array{
    $resultados = $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetchAllAssociative();

    foreach ($resultados as &$tarea) {
        $tarea['ia'] = (bool)$tarea['ia'];
        
        if ($tarea['fecha_publicacion']) {
            $tarea['fecha_publicacion'] = (new \DateTime($tarea['fecha_publicacion']))->format('d-m-Y H:i:s');
        }
        if ($tarea['fecha_vencimiento']) {
            $tarea['fecha_vencimiento'] = (new \DateTime($tarea['fecha_vencimiento']))->format('d-m-Y H:i:s');
        }
    }

    return $resultados;
    }


    /*
    * CREAR
    */
    public function crearTarea(array $datos){
        $sql = "INSERT INTO tarea (nombre_tarea, descripcion, fecha_publicacion, fecha_vencimiento, ia, estado, prioridad, categoria_id, grupo_id) 
            VALUES (:nombre, :desc, :fPub, :fVenc, :ia, :estado, :prioridad, :catId, :grupoId)";
       
        $params = [
            'nombre' => $datos['nombre_tarea'],
            'desc' => $datos['descripcion'],
            //Fechas
            'fPub' => $datos['fecha_publicacion'],
            'fVenc' => $datos['fecha_vencimiento'],
            //Boolean
            'ia' => $datos['ia'] ? 1 : 0, // 1 = true, 0 = false
            //Enums
            'estado'=> $datos['estado'],
            'prioridad' => $datos['prioridad'],
            //FKs
            'catId' => $datos['categoria_id'],
            'grupoId' => $datos['grupo_id'],
        ];

        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }
    /*
    * ELIMINAR
    */
    public function eliminarTarea(int $id){
        $sql = 'DELETE FROM tarea WHERE id = :id';

        $params = [
        'id' => $id 
        ];

        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }

    /*
    * VER
    */
    public function verTodasTareas(){
        $sql = "SELECT id,nombre_tarea,descripcion,fecha_publicacion, fecha_vencimiento, ia, estado, prioridad, categoria_id, grupo_id
                FROM tarea";

        $resultados = $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAllAssociative();

        $tareasFormateadas = [];
        
        foreach ($resultados as $tarea) {
            //transformar IA (tinyint) a Booleano real
            $tarea['ia'] = (bool)$tarea['ia'];

            //transformar fechas al formato correcto (d-m-Y H:i:s)
            if ($tarea['fecha_publicacion']) {
                $fechaPub = new \DateTime($tarea['fecha_publicacion']);
                $tarea['fecha_publicacion'] = $fechaPub->format('d-m-Y H:i:s');
            }
            if ($tarea['fecha_vencimiento']) {
                $fechaVenc = new \DateTime($tarea['fecha_vencimiento']);
                $tarea['fecha_vencimiento'] = $fechaVenc->format('d-m-Y H:i:s');
            }
            $tareasFormateadas[] = $tarea;
            }

            return $tareasFormateadas;
    }

    public function verTareasPorEstado(string $estado){
        $sql = "SELECT id, nombre_tarea, descripcion, fecha_publicacion, fecha_vencimiento, ia, estado, prioridad, categoria_id, grupo_id 
            FROM tarea WHERE estado = :estado";
            
        return $this->ejecutarYFormatear($sql, ['estado' => $estado]);
        
    }
    public function verTareasPorPrioridad(string $prioridad){
        $sql = "SELECT id, nombre_tarea, descripcion, fecha_publicacion, fecha_vencimiento, ia, estado, prioridad, categoria_id, grupo_id 
            FROM tarea WHERE prioridad = :prioridad";
            
    return $this->ejecutarYFormatear($sql, ['prioridad' => $prioridad]);
    }
    public function verTareasPorCategoria(int $categoria){
        $sql = "SELECT id, nombre_tarea, descripcion, fecha_publicacion, fecha_vencimiento, ia, estado, prioridad, categoria_id, grupo_id 
            FROM tarea WHERE categoria_id = :categoria";
            
    return $this->ejecutarYFormatear($sql, ['categoria' => $categoria]);
    }
    public function verTareasPorFechaVencimiento(string $fechaSeleccionada){
        // 1. Validamos y normalizamos la fecha que viene de Vue (ej: "07-02-2026")
        // La convertimos a objeto y luego a string en formato MySQL
        $fechaObjeto = new \DateTime($fechaSeleccionada);
        $fechaSql = $fechaObjeto->format('Y-m-d'); 

        // 2. Usamos LIKE o DATE() para comparar solo el día si la columna es DATETIME
        $sql = "SELECT id, nombre_tarea, descripcion, fecha_publicacion, fecha_vencimiento, ia, estado, prioridad, categoria_id, grupo_id 
                FROM tarea 
                WHERE DATE(fecha_vencimiento) = :fecha";

        return $this->ejecutarYFormatear($sql, ['fecha' => $fechaSql]);
    }

    /*
    * ACTUALIZAR
    */
    public function actualizarNombreTarea(int $id, string $nuevoNombre){
        $sql = "UPDATE tarea SET nombre_tarea = :nombre WHERE id = :id";
        $params = [
        'nombre' => $nuevoNombre,
        'id'     => $id
        ];

    $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }

    public function actualizarEstadoTarea(int $id, string $nuevoEstado){
        $sql = "UPDATE tarea SET estado = :estado WHERE id = :id";
        $params = [
        'estado' => $nuevoEstado,
        'id'     => $id
        ];

        $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }
    public function actualizarDescripcionTarea(int $id, string $nuevaDescripcion){
        $sql = "UPDATE tarea SET descripcion = :descripcion WHERE id = :id";
        $params = [
        'descripcion' => $nuevaDescripcion,
        'id'     => $id
        ];

        $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }
    public function actualizarCategoriaTarea(int $id, int $nuevaCategoriaId){
        $sql = "UPDATE tarea SET categoria_id = :nuevaCategoriaId WHERE id = :id";
        $params = [
        'nuevaCategoriaId' => $nuevaCategoriaId,
        'id'     => $id
        ];

        $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }
    public function actualizarFechaVencimientoTarea(int $id, string $nuevaFecha){
        $fechaObjeto = new \DateTime($nuevaFecha);
        $fechaFormateada = $fechaObjeto->format('Y-m-d H:i:s');

        $sql = "UPDATE tarea SET fecha_vencimiento = :nuevaFecha WHERE id = :id";
    
        $params = [
            'nuevaFecha' => $fechaFormateada,
            'id'         => $id
        ];

        $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }
}
