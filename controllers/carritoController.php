<?php

include_once(__DIR__ . "/../models/Carrito.php");
include_once(__DIR__ . "/../models/ProductoDAO.php");

class carritoController
{
    public function index()
    {
        $lineas = Carrito::lineas();
        $total = Carrito::total($lineas);
        $titulo = "Carrito";
        $view = __DIR__ . "/../views/carrito/index.php";
        include_once __DIR__ . "/../views/main.php";
    }

    // POST desde el formulario de cada producto. Patrón Post-Redirect-Get: nunca pinta vista.
    public function agregar()
    {
        $id = $this->post('producto_id');
        $cantidad = $this->post('cantidad');
        $volver = $this->post('volver');

        $destino = preg_match('/^[a-z0-9-]+$/', $volver)
            ? "?controller=producto&action=categoria&slug=" . $volver
            : "?controller=categoria";

        if (
            $_SERVER['REQUEST_METHOD'] === 'POST'
            && ctype_digit($id) && ctype_digit($cantidad)
            && $cantidad >= 1 && $cantidad <= Carrito::MAX_UNIDADES
        ) {
            $producto = ProductoDAO::getById((int) $id);
            if ($producto !== null) {
                Carrito::agregar($producto->getId(), (int) $cantidad);
                $_SESSION['aviso'] = "Añadido al carrito: " . (int) $cantidad . " × " . $producto->getNombre();
            }
        }

        header("Location: " . url . "index.php" . $destino);
        exit;
    }

    public function quitar()
    {
        $id = $this->post('producto_id');
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ctype_digit($id)) {
            Carrito::quitar((int) $id);
        }
        header("Location: " . url . "index.php?controller=carrito");
        exit;
    }

    public function vaciar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Carrito::vaciar();
        }
        header("Location: " . url . "index.php?controller=carrito");
        exit;
    }

    // Lee un campo de $_POST como texto (si no existe o es un array, devuelve '')
    private function post($campo)
    {
        return isset($_POST[$campo]) && is_string($_POST[$campo]) ? trim($_POST[$campo]) : '';
    }
}
