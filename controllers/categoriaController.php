<?php
// Carga las dependencias del modelo usando rutas relativas seguras
include_once(__DIR__ . "/../models/CategoriaDAO.php");
include_once(__DIR__ . "/../models/Categoria.php");

class CategoriaController
{
    // Método por defecto: muestra el listado o redirige al formulario de crear
    public function index(): void
    {
        $this->crear();
    }

    // Muestra la vista con el formulario HTML para crear una categoría
    public function crear(): void
    {
        include_once(__DIR__ . "/../views/categoria/crear.php");
    }

    // Recibe los datos del formulario POST y los guarda en la BD
    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $slug = $_POST['slug'] ?? '';
            $descripcion = !empty($_POST['descripcion']) ? $_POST['descripcion'] : null;
            $activa = isset($_POST['activa']);

            // Instanciamos el objeto Categoria con sus tipos correspondientes
            $categoria = new Categoria($nombre, $slug, $descripcion, $activa);

            // Guardamos en MySQL usando CategoriaDAO
            $exito = CategoriaDAO::store($categoria);

            if ($exito) {
                echo "<h2 style='color: green;'>¡Categoría guardada con éxito en MySQL!</h2>";
                echo "<a href='" . url . "index.php?controller=Categoria&action=crear'>Volver al formulario</a>";
            } else {
                echo "<h2 style='color: red;'>Error al guardar la categoría.</h2>";
            }
        }
    }
}
?>