<?php

namespace Resena;

class ResenaDAO
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }


    /*crear reseña*/
    public function crearResena($datos)
    {
        $sql = "INSERT INTO resenas (id_restaurante, id_usuario, id_reserva, calificacion, comentario, fecha_resena)
            VALUES (:id_restaurante, :id_usuario, :id_reserva, :calificacion, :comentario, :fecha_resena)";

        $sentencia = $this->conexion->prepare($sql);
        $sentencia->bindValue(":id_restaurante", $datos['id_restaurante']);
        $sentencia->bindValue(":id_usuario", $datos['id_usuario']);
        $sentencia->bindValue(":id_reserva", $datos['id_reserva']);
        $sentencia->bindValue(":calificacion", $datos['calificacion']);
        $sentencia->bindValue(":comentario", $datos['comentario']);
        $sentencia->bindValue(":fecha_resena", $datos['fecha_resena']);

        return $sentencia->execute();
    }

    /*Comporbar si ya hay una reseña para esa reserva*/
    public function existeResenaParaReserva($idReserva)
    {
        $sql = "SELECT COUNT(*) FROM resenas WHERE id_reserva = :id_reserva";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['id_reserva' => $idReserva]);
        return $stmt->fetchColumn() > 0;
    }


}
