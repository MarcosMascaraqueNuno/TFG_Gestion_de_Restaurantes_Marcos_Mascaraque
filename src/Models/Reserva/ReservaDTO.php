<?php
namespace Reserva;

class ReservaDTO
{
    private $id;
    private $idRestaurante;
    private $idUsuario;
    private $idMesa;
    private $fechaReserva;
    private $horaReserva;
    private $numeroComensales;
    private $estadoReserva;
    private $comentarios;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getIdRestaurante()
    {
        return $this->idRestaurante;
    }

    public function setIdRestaurante($idRestaurante)
    {
        $this->idRestaurante = $idRestaurante;
    }

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    public function getIdMesa()
    {
        return $this->idMesa;
    }

    public function setIdMesa($idMesa)
    {
        $this->idMesa = $idMesa;
    }

    public function getFechaReserva()
    {
        return $this->fechaReserva;
    }

    public function setFechaReserva($fechaReserva)
    {
        $this->fechaReserva = $fechaReserva;
    }

    public function getHoraReserva()
    {
        return $this->horaReserva;
    }

    public function setHoraReserva($horaReserva)
    {
        $this->horaReserva = $horaReserva;
    }

    public function getNumeroComensales()
    {
        return $this->numeroComensales;
    }

    public function setNumeroComensales($numeroComensales)
    {
        $this->numeroComensales = $numeroComensales;
    }

    public function getEstadoReserva()
    {
        return $this->estadoReserva;
    }

    public function setEstadoReserva($estadoReserva)
    {
        $this->estadoReserva = $estadoReserva;
    }

    public function getComentarios()
    {
        return $this->comentarios;
    }

    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;
    }
}

?>
