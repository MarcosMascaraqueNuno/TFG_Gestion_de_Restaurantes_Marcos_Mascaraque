<?php

namespace Restaurante;

use Exception;
use PDO;
use Resena\ResenaDTO;

class RestauranteDAO
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;

        if (!$this->conexion) {
            die("Error: No se pudo establecer la conexión a la base de datos.");
        }
    }

    /*Obetener todos los restaurantes*/
    public function obtenerRestaurantes()
    {
        try {
            $stmt = $this->conexion->prepare("SELECT id, nombre, direccion, ciudad, telefono, email, capacidad_total, url_menu_pdf, horario_apertura, horario_cierre, descripcion, url_foto, precio_medio FROM restaurantes");
            $stmt->execute();

            $restaurantes = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $restauranteDTO = new RestauranteDTO();
                $restauranteDTO->setId($row['id']);
                $restauranteDTO->setNombre($row['nombre']);
                $restauranteDTO->setDireccion($row['direccion']);
                $restauranteDTO->setCiudad($row['ciudad']);
                $restauranteDTO->setTelefono($row['telefono']);
                $restauranteDTO->setEmail($row['email']);
                $restauranteDTO->setCapacidadTotal($row['capacidad_total']);
                $restauranteDTO->setUrlMenuPdf($row['url_menu_pdf']);
                $restauranteDTO->setHorarioApertura($row['horario_apertura']);
                $restauranteDTO->setHorarioCierre($row['horario_cierre']);
                $restauranteDTO->setDescripcion($row['descripcion']);
                $restauranteDTO->setUrlFoto($row['url_foto']);
                $restauranteDTO->setPrecioMedio($row['precio_medio']);
                $restaurantes[] = $restauranteDTO;
            }

            return $restaurantes;
        } catch (Exception $e) {
            die("Error al obtener los restaurantes: " . $e->getMessage());
        }
    }

    /* Buscar restaurantes segun el criterio, tipo de comida y ciudad */
    public function buscarRestaurantes($criterio, $tipoComida = null, $ciudad = null)
    {
        try {
            $sql = "SELECT restaurantes.id, restaurantes.nombre, restaurantes.direccion, restaurantes.telefono, restaurantes.email, 
                restaurantes.capacidad_total, restaurantes.url_menu_pdf, restaurantes.horario_apertura, 
                restaurantes.horario_cierre, restaurantes.descripcion, restaurantes.url_foto, restaurantes.precio_medio, 
                restaurantes.ciudad
                FROM restaurantes
                LEFT JOIN restaurantes_tipos_comida ON restaurantes.id = restaurantes_tipos_comida.restaurante_id
                LEFT JOIN tipos_comida ON restaurantes_tipos_comida.tipo_comida_id = tipos_comida.id
                WHERE restaurantes.nombre LIKE :criterio";

            if (!empty($tipoComida)) {
                $sql .= " AND tipos_comida.id = :tipoComida";
            }

            if (!empty($ciudad)) {
                $sql .= " AND restaurantes.ciudad LIKE :ciudad";
            }

            $sql .= " GROUP BY restaurantes.id";

            $stmt = $this->conexion->prepare($sql);
            $criterioLike = "%" . $criterio . "%";
            $stmt->bindParam(':criterio', $criterioLike, PDO::PARAM_STR);

            if (!empty($tipoComida)) {
                $stmt->bindParam(':tipoComida', $tipoComida, PDO::PARAM_INT);
            }

            if (!empty($ciudad)) {
                $ciudadLike = "%" . $ciudad . "%";
                $stmt->bindParam(':ciudad', $ciudadLike, PDO::PARAM_STR);
            }

            $stmt->execute();

            $restaurantes = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $restauranteDTO = new RestauranteDTO();
                $restauranteDTO->setId($row['id']);
                $restauranteDTO->setNombre($row['nombre']);
                $restauranteDTO->setDireccion($row['direccion']);
                $restauranteDTO->setTelefono($row['telefono']);
                $restauranteDTO->setEmail($row['email']);
                $restauranteDTO->setCapacidadTotal($row['capacidad_total']);
                $restauranteDTO->setUrlMenuPdf($row['url_menu_pdf']);
                $restauranteDTO->setHorarioApertura($row['horario_apertura']);
                $restauranteDTO->setHorarioCierre($row['horario_cierre']);
                $restauranteDTO->setDescripcion($row['descripcion']);
                $restauranteDTO->setUrlFoto($row['url_foto']);
                $restauranteDTO->setPrecioMedio($row['precio_medio']);
                $restauranteDTO->setCiudad($row['ciudad']);
                $restaurantes[] = $restauranteDTO;
            }

            return $restaurantes;
        } catch (Exception $e) {
            die("Error al buscar los restaurantes: " . $e->getMessage());
        }
    }

    /* Obtener el tipo de usuario por su id */
    public function obtenerTipoUsuario($usuarioId)
    {
        try {
            $stmt = $this->conexion->prepare("SELECT tipo_usuario FROM usuarios WHERE id = ?");
            $stmt->execute([$usuarioId]);

            $tipoUsuario = $stmt->fetch(PDO::FETCH_ASSOC);
            return $tipoUsuario['tipo_usuario'] ?? null;
        } catch (Exception $e) {
            die("Error al obtener tipo de usuario: " . $e->getMessage());
        }
    }

    /* Obetener los restaurantes por propietario con filtros opcionales */
    public function obtenerRestaurantesPorPropietario($usuarioId, $criterio = '', $tipoComida = '', $ciudad = '')
    {
        try {
            $sql = "SELECT id, nombre, direccion, telefono, email, capacidad_total, url_menu_pdf, horario_apertura, horario_cierre, descripcion, url_foto, precio_medio, ciudad 
                FROM restaurantes 
                WHERE propietario_id = :usuarioId";

            if (!empty($criterio)) {
                $sql .= " AND nombre LIKE :criterio";
            }

            if (!empty($tipoComida)) {
                $sql .= " AND tipo_comida = :tipoComida";
            }

            if (!empty($ciudad)) {
                $sql .= " AND ciudad = :ciudad";
            }

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);

            if (!empty($criterio)) {
                $criterioLike = '%' . $criterio . '%';
                $stmt->bindParam(':criterio', $criterioLike, PDO::PARAM_STR);
            }

            if (!empty($tipoComida)) {
                $stmt->bindParam(':tipoComida', $tipoComida, PDO::PARAM_INT);
            }

            if (!empty($ciudad)) {
                $stmt->bindParam(':ciudad', $ciudad, PDO::PARAM_STR);
            }

            $stmt->execute();

            $restaurantes = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $restauranteDTO = new RestauranteDTO();
                $restauranteDTO->setId($row['id']);
                $restauranteDTO->setNombre($row['nombre']);
                $restauranteDTO->setDireccion($row['direccion']);
                $restauranteDTO->setCiudad($row['ciudad']);
                $restauranteDTO->setTelefono($row['telefono']);
                $restauranteDTO->setEmail($row['email']);
                $restauranteDTO->setCapacidadTotal($row['capacidad_total']);
                $restauranteDTO->setUrlMenuPdf($row['url_menu_pdf']);
                $restauranteDTO->setHorarioApertura($row['horario_apertura']);
                $restauranteDTO->setHorarioCierre($row['horario_cierre']);
                $restauranteDTO->setDescripcion($row['descripcion']);
                $restauranteDTO->setPrecioMedio($row['precio_medio']);
                $restauranteDTO->setUrlFoto($row['url_foto'] ?? null);

                $restaurantes[] = $restauranteDTO;
            }

            return $restaurantes;
        } catch (Exception $e) {
            die("Error al obtener los restaurantes por propietario: " . $e->getMessage());
        }
    }

    /* Obtener un restaurante por su id */
    public function obtenerPorId($id)
    {
        try {
            $stmt = $this->conexion->prepare("
            SELECT id, nombre, direccion, numero_direccion, telefono, email, capacidad_total, url_menu_pdf, horario_apertura, horario_cierre, descripcion, url_foto, precio_medio, ciudad 
            FROM restaurantes 
            WHERE id = :id
        ");
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $restauranteDTO = new RestauranteDTO();
                $restauranteDTO->setId($row['id']);
                $restauranteDTO->setNombre($row['nombre']);
                $restauranteDTO->setDireccion($row['direccion']);
                $restauranteDTO->setNumero($row['numero_direccion']);
                $restauranteDTO->setTelefono($row['telefono']);
                $restauranteDTO->setEmail($row['email']);
                $restauranteDTO->setCapacidadTotal($row['capacidad_total']);
                $restauranteDTO->setUrlMenuPdf($row['url_menu_pdf']);
                $restauranteDTO->setHorarioApertura($row['horario_apertura']);
                $restauranteDTO->setHorarioCierre($row['horario_cierre']);
                $restauranteDTO->setDescripcion($row['descripcion']);
                $restauranteDTO->setUrlFoto($row['url_foto']);
                $restauranteDTO->setPrecioMedio($row['precio_medio']);
                $restauranteDTO->setCiudad($row['ciudad']);
                return $restauranteDTO;
            } else {
                return null;
            }
        } catch (Exception $e) {
            die("Error al obtener el restaurante por ID: " . $e->getMessage());
        }
    }

    public function obtenerFotosRestaurante($restauranteId) {
        $sql = "SELECT url_foto FROM fotos_restaurante WHERE restaurante_id = :restaurante_id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':restaurante_id', $restauranteId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /* Obtener un máximo de 3 reseñas aleatorias para un restaurante especifico */
    public function obtenerResenasPorRestaurante($restauranteId)
    {
        try {
            $stmt = $this->conexion->prepare("SELECT id_reseña, id_restaurante, id_usuario, calificacion, comentario, fecha_resena FROM resenas WHERE id_restaurante = :restauranteId ORDER BY RAND() LIMIT 3");
            $stmt->bindParam(':restauranteId', $restauranteId, PDO::PARAM_INT);
            $stmt->execute();

            $resenas = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $resena = new ResenaDTO(
                    $row['calificacion'],
                    $row['comentario'],
                    $row['fecha_resena'],
                    $row['id_restaurante'],
                    $row['id_usuario'],
                    'Nombre Usuario'
                );

                $resena->setId($row['id_reseña']);

                $resenas[] = $resena;
            }

            return $resenas;
        } catch (Exception $e) {
            die("Error al obtener las reseñas: " . $e->getMessage());
        }
    }

    /* Obtener la calificación media de un restaurante */
    public function obtenerMediaCalificacion($restauranteId) {
        $sql = "SELECT AVG(calificacion) AS media_calificacion FROM `resenas` WHERE `id_restaurante` = :restaurante_id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':restaurante_id', $restauranteId, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['media_calificacion'] ? round($resultado['media_calificacion'], 1) : 0;
    }


    public function actualizarRestaurante(RestauranteDTO $restaurante) {
        $sql = "UPDATE restaurantes SET 
            nombre = :nombre, 
            direccion = :direccion, 
            numero_direccion = :numero_direccion,
            ciudad = :ciudad,
            telefono = :telefono,
            email = :email,
            capacidad_total = :capacidad_total,
            horario_apertura = :horario_apertura,
            horario_cierre = :horario_cierre,
            descripcion = :descripcion,
            precio_medio = :precio_medio
            WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(':id', $restaurante->getId(), \PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $restaurante->getNombre(), \PDO::PARAM_STR);
        $stmt->bindParam(':direccion', $restaurante->getDireccion(), \PDO::PARAM_STR);
        $stmt->bindParam(':numero_direccion', $restaurante->getNumero(), \PDO::PARAM_STR);
        $stmt->bindParam(':ciudad', $restaurante->getCiudad(), \PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $restaurante->getTelefono(), \PDO::PARAM_STR);
        $stmt->bindParam(':email', $restaurante->getEmail(), \PDO::PARAM_STR);
        $stmt->bindParam(':capacidad_total', $restaurante->getCapacidadTotal(), \PDO::PARAM_INT);
        $stmt->bindParam(':horario_apertura', $restaurante->getHorarioApertura(), \PDO::PARAM_STR);
        $stmt->bindParam(':horario_cierre', $restaurante->getHorarioCierre(), \PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $restaurante->getDescripcion(), \PDO::PARAM_STR);
        $stmt->bindParam(':precio_medio', $restaurante->getPrecioMedio(), \PDO::PARAM_STR);

        return $stmt->execute();
    }

}



?>
