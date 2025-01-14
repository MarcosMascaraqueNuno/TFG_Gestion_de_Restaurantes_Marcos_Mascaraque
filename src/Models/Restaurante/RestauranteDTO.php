<?php

namespace Restaurante;

class RestauranteDTO
{
    private $id;
    private $nombre;
    private $direccion;
    private $numero_direccion;
    private $ciudad;
    private $telefono;
    private $email;
    private $capacidad_total;
    private $url_menu_pdf;
    private $horario_apertura;
    private $horario_cierre;
    private $descripcion;
    private $url_foto;
    private $precio_medio;
    private $propietario_id;

    public function __construct(
        $id = null,
        $nombre = null,
        $direccion = null,
        $numero_direccion = null,
        $ciudad = null,
        $telefono = null,
        $email = null,
        $capacidad_total = null,
        $url_menu_pdf = null,
        $horario_apertura = null,
        $horario_cierre = null,
        $descripcion = null,
        $url_foto = null,
        $precio_medio = null,
        $propietario_id = null
    )
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->numero_direccion = $numero_direccion;
        $this->ciudad = $ciudad;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->capacidad_total = $capacidad_total;
        $this->url_menu_pdf = $url_menu_pdf;
        $this->horario_apertura = $horario_apertura;
        $this->horario_cierre = $horario_cierre;
        $this->descripcion = $descripcion;
        $this->url_foto = $url_foto;
        $this->precio_medio = $precio_medio;
        $this->propietario_id = $propietario_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    public function getNumero()
    {
        return $this->numero_direccion;
    }

    public function setNumero($numero_direccion)
    {
        $this->numero_direccion = $numero_direccion;
    }

    public function getCiudad()
    {
        return $this->ciudad;
    }


    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getCapacidadTotal()
    {
        return $this->capacidad_total;
    }

    public function setCapacidadTotal($capacidad_total)
    {
        $this->capacidad_total = $capacidad_total;
    }

    public function getUrlMenuPdf()
    {
        return $this->url_menu_pdf;
    }

    public function setUrlMenuPdf($url_menu_pdf)
    {
        $this->url_menu_pdf = $url_menu_pdf;
    }

    public function getHorarioApertura()
    {
        return $this->horario_apertura;
    }

    public function setHorarioApertura($horario_apertura)
    {
        $this->horario_apertura = $horario_apertura;
    }

    public function getHorarioCierre()
    {
        return $this->horario_cierre;
    }

    public function setHorarioCierre($horario_cierre)
    {
        $this->horario_cierre = $horario_cierre;
    }


    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function getUrlFoto()
    {
        return $this->url_foto;
    }

    public function setUrlFoto($url_foto)
    {
        $this->url_foto = $url_foto;
    }

    public function getPrecioMedio()
    {
        return $this->precio_medio;
    }

    public function setPrecioMedio($precio_medio)
    {
        $this->precio_medio = $precio_medio;
    }

    public function getPropietarioId()
    {
        return $this->propietario_id;
    }

    public function setPropietarioId($propietario_id)
    {
        $this->propietario_id = $propietario_id;
    }

    public function getMenuUrl()
    {
        return $this->url_menu_pdf;
    }

}

?>
