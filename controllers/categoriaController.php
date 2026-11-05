<?php
include_once("models/CategoriaDAO.php");
include_once("models/Categoria.php");

class CategoriaController {
    public function index() {
        $categorias = CategoriaDAO::getAll();
        $view = "views/categorias/lista-categorias.php";
        include_once 'views/main.php';
    }

    public function crearCategoria() {
        $view = "views/categorias/crear-categorias.php";
        include_once "/../views/categoria/crear.php";
    }

    // Recibe los datos del formulario POST y los guarda en la BD
    public function guardarCategoria() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $slug = $_POST['slug'] ?? '';
            $descripcion = !empty($_POST['descripcion']) ? $_POST['descripcion'] : null;
            $activa = isset($_POST['activa']);

            // Instanciamos el objeto Categoria con sus tipos correspondientes
            $categoria = new Categoria($nombre, $slug, $descripcion, $activa);

            // Guardamos en MySQL usando CategoriaDAO
            $exito = CategoriaDAO::guardarCategoria($categoria);

            if ($exito) {
                echo "<h2 style='color: green;'>¡Categoría guardada con éxito en MySQL!</h2>";
                echo "<a href='" . url . "index.php?controller=Categoria&action=crear'>Volver al formulario</a>";
            } else {
                echo "<h2 style='color: red;'>Error al guardar la categoría.</h2>";
            }
        }
    }

    public function eliminarCategoria() {
        if (!isset($_GET['id']) || !ctype_digit((string) $_GET['id'])) {
            header('Location:?controller=categoria');
            exit;
        }

        CategoriaDAO::eliminarCategoria((int) $_GET['id']);
        header('Location:?controller=categoria');
        exit;
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