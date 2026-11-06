<?php

abstract class Productos {
    protected $id;
    protected $categoria_id;
    protected $sku;
    protected $slug;
    protected $nombre;
    protected $descripcion;
    protected $precio;
    protected $imagen;
    protected $activo;

    // CONSTRUCTOR

    public function __construct($nombre = null, $precio = null) {
        if ($nombre !== null) {
            $this->nombre = $nombre;
        }
        if ($precio !== null) {
            $this->precio = $precio;
        }
    }
    
    // GETTERS

    public function getId() {
        return $this->id;
    }
    public function getCategoriaId() {
        return $this->categoria_id;
    }
    public function getSku() {
        return $this->sku;
    }
    public function getSlug() {
        return $this->slug;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function getDescripcion() {
        return $this->descripcion;
    }
    public function getImagen() {
        return $this->imagen;
    }
    public function isActivo() {
        return (bool) $this->activo;
    }
    public function getPrecio() {
        return (float) $this->precio;
    }

    // SETTERS

    public function setNombre($nombre) {
        $this->nombre = $nombre;
        return $this;
    }
    public function setPrecio($precio) {
        $this->precio = $precio;
        return $this;
    }
}