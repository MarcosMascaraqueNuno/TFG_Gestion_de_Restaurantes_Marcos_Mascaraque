<?php

namespace Resena;

class ResenaDTO
{
    private $id;
    private $idUsuario;
    private $calificacion;
    private $comentario;
    private $fechaResena;
    private $idRestaurante;
    private $nombreUsuario;

    public function __construct($calificacion, $comentario, $fechaResena, $idRestaurante, $idUsuario, $nombreUsuario)
    {
        $this->calificacion = $calificacion;
        $this->comentario = $comentario;
        $this->fechaResena = $fechaResena;
        $this->idRestaurante = $idRestaurante;
        $this->idUsuario = $idUsuario;
        $this->nombreUsuario = $nombreUsuario;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    public function setCalificacion($calificacion)
    {
        $this->calificacion = $calificacion;
    }

    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
    }

    public function setFechaResena($fechaResena)
    {
        $this->fechaResena = $fechaResena;
    }

    public function setIdRestaurante($idRestaurante)
    {
        $this->idRestaurante = $idRestaurante;
    }

    public function setNombreUsuario($nombreUsuario)
    {
        $this->nombreUsuario = $nombreUsuario;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function getCalificacion()
    {
        return $this->calificacion;
    }

    public function getComentario()
    {
        return $this->comentario;
    }

    public function getFechaResena()
    {
        return $this->fechaResena;
    }

    public function getIdRestaurante()
    {
        return $this->idRestaurante;
    }

    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }
}
