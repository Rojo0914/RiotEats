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

    public function api() {
        $metodo = $_SERVER['REQUEST_METHOD'];

        if($metodo == "GET") {
            $productos = ProductoDAO::getAllJson();
            echo json_encode($productos);    
        } else if($metodo == "DELETE") {
            $datos = json_decode(file_get_contents("php://input"), true);
            echo json_decode(file_get_contents("php://input"), true);
        } else if($metodo == "POST") {
            $datos = json_decode(file_get_contents("php://input"), true);

        }

        // switch ($metodo) {
            // case 'GET':
                // if($_GET['funcion'] == "getById") {

                // }
                // if($_GET['funcion'] == "getAllJson") {
                //     echo ProductoDAO::getAllJson();
                // }

                // break;
            // case 'POST':


                // break;
            // case 'PUT':


                // break;
            // case 'DELETE':


                // break;
            // default:
            //     break;
        // }

        // get = consultar
        // post = enviar / crear
        // put = reemplazar
        // patch = editar parcialmente
        // delete = eliminar
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