<?php
namespace Reserva;

use PDO;
use Restaurantes\Exception;

class ReservaDAO
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    /*Obtener todas las reservas de un restaurante filtradas por un dia y hora en especifico*/
    public function obtenerReservasPorRestaurante($restauranteId, $fechaReserva = null, $estado = null)
    {
        $sql = "SELECT *, nombre AS nombre_cliente, email AS email_cliente, telefono AS telefono_cliente 
            FROM reservas 
            JOIN usuarios ON id_usuario = id 
            WHERE id_restaurante = :restauranteId";

        if ($fechaReserva) {
            $sql .= " AND fecha_reserva = :fechaReserva";
        }

        if ($estado) {
            $sql .= " AND estado_reserva = :estado";
        }

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':restauranteId', $restauranteId, PDO::PARAM_INT);

        if ($fechaReserva) {
            $stmt->bindParam(':fechaReserva', $fechaReserva);
        }
        if ($estado) {
            $stmt->bindParam(':estado', $estado);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*Obtener el nombre del restaurante a traves de la id*/
    public function obtenerNombreRestaurante($restauranteId)
    {
        $sql = "SELECT nombre FROM restaurantes WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$restauranteId]);
        return $stmt->fetchColumn();
    }

    /*Obtener todas las reservas de un usuario específico*/
    public function obtenerReservasPorUsuario($usuarioId)
    {
        $sql = "SELECT id_reserva, fecha_reserva, hora_reserva, numero_comensales, 
               estado_reserva, comentarios, rest.nombre AS nombre_restaurante
            FROM reservas
            JOIN restaurantes rest ON reservas.id_restaurante = rest.id
            WHERE reservas.id_usuario = :usuario_id
            ORDER BY reservas.fecha_reserva DESC, reservas.hora_reserva DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['usuario_id' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*Obtener detalles de una reserva por su id*/
    public function obtenerReservaPorId($idReserva)
    {
        try {
            $stmt = $this->conexion->prepare("
            SELECT * 
            FROM reservas 
            WHERE id_reserva = :id_reserva
        ");
            $stmt->bindValue(":id_reserva", $idReserva, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Error al obtener la reserva: " . $e->getMessage());
        }
    }

    /*Actualizar las reservas que ya han pasado de fecha y hora*/
    public function actualizarReservasFinalizadas() {
        $sql = "
        UPDATE reservas 
        SET estado_reserva = 'Finalizada' 
        WHERE fecha_reserva < CURDATE()
        OR (fecha_reserva = CURDATE() AND hora_reserva < CURTIME())
    ";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute();
    }

    /*Calelar la reserva*/
    public function cancelarReserva($reservaId)
    {
        $sql = "UPDATE reservas SET estado_reserva = 'Cancelada' WHERE id_reserva = :reserva_id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':reserva_id', $reservaId, \PDO::PARAM_INT);
        $stmt->execute();
    }

}


?>
