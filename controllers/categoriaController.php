<?php
include_once("models/CategoriaDAO.php");
include_once("models/Categoria.php");

class categoriaController {
    public function index() {
        $categorias = CategoriaDAO::getAll();

        $imagenPorDefecto = 'img/Carta/Tabla-Pizarra.png';

        $view = "views/carta/carta.php";
        include_once 'views/main.php';
    }

    public function crearCategoria() {
        $view = "views/categorias/crear-categorias.php";
        include_once 'views/main.php';
    }

    public function mostrarCategorias() {
        if (!isset($_GET['id']) || !ctype_digit((string) $_GET['id'])) {
            header('Location:?controller=categoria');
            exit;
        }

        $producto = CategoriaDAO::getById((int) $_GET['id']);
        if ($producto === null) {
            header('Location:?controller=categoria');
            exit;
        }

        $view = "views/categorias/vista.php";
        include_once 'views/main.php';
    }
}
?>