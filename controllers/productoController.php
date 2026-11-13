<?php
include_once("models/ProductoDAO.php");

class ProductoController {
    public function categoria() {
        if (!isset($_GET['id'])) {
            header("Location: " . url);
            exit;
        }

        $categoriaId = (int)$_GET['id'];

        $categoria = ProductoDAO::getCategoriaById($categoriaId);
        $productos = ProductoDAO::getByCategoria($categoriaId);

        $view = "views/carta/productosCategoria.php";
        include_once("views/main.php");
    }

    public function detalle() {
        if (!isset($_GET['id'])) {
            header("Location: " . url);
            exit;
        }

        $id = (int)$_GET['id'];
        $producto = ProductoDAO::getById($id);

        if (!$producto) {
            header("Location: " . url);
            exit;
        }

        // Solo las hamburguesas tienen extras (y solo de sus propios ingredientes)
        $extras = ($producto instanceof Hamburguesa) ? ProductoDAO::getExtrasActivos($id) : [];

        $view = "views/carta/productoExtras.php";
        include_once("views/main.php");
    }
}