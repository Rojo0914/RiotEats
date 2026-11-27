<?php

// MIRAR PORQUE FALTAN COSAS

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
        header('Content-Type: application/json; charset=utf-8');

        // 1. Si piden los datos (GET), devolvemos todos los productos
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $productos = ProductoDAO::getAllJson();
            echo json_encode($productos);
            exit();
        }

        // 2. Si piden borrar (DELETE), leemos el ID enviado y llamamos al DAO
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $datos = json_decode(file_get_contents("php://input"), true);

            if (isset($datos['id']) && $datos['id'] != "") {
                $id = (int) $datos['id'];
                $borrado = ProductoDAO::eliminar($id);

                if ($borrado) {
                    echo json_encode(["ok" => true, "mensaje" => "Producto eliminado"]);
                } else {
                    echo json_encode(["ok" => false, "mensaje" => "No se encontró el ID o no se pudo borrar"]);
                }
            } else {
                echo json_encode(["ok" => false, "mensaje" => "Falta el ID del producto"]);
            }
            exit();
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