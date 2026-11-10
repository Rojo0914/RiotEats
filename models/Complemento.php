<?php

include_once(__DIR__ . "/Producto.php");

class Complemento extends Producto
{
    public function __construct($nombre = null, $descripcion = null, $precio = null, $imagen = null)
    {
        parent::__construct($nombre, $descripcion, $precio, $imagen);
    }
}
