<?php

abstract class Producto
{
    const TYPE_CAMISETA = 1;
    const TYPE_PANTALONES = 2;

    // Los nombres deben coincidir con las columnas de la tabla (camisetas)
    protected $id;
    protected $nombre;
    protected $talla;
    protected $precio;

    /**
     * fetch_object() rellena las propiedades ANTES de llamar al constructor.
     * Por eso no hay que pisarlas con valores vacíos cuando se instancia sin argumentos.
     */
    public function __construct($nombre = null, $talla = null, $precio = null)
    {
        if ($nombre !== null) {
            $this->nombre = $nombre;
        }
        if ($talla !== null) {
            $this->talla = $talla;
        }
        if ($precio !== null) {
            $this->precio = $precio;
        }
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getTalla()
    {
        return $this->talla;
    }

    public function setTalla($talla)
    {
        $this->talla = $talla;
        return $this;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}
