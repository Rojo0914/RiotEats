<?php

require_once __DIR__ . '/Producto.php';

class Hamburguesa extends Producto {
    public function getEtiquetaCategoria(): string {
        return "Hamburguesas";
    }
}