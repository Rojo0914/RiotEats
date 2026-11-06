<?php
include_once("config/dataBase.php");
include_once("models/Categoria.php");

class CategoriaDAO {
    
    public static function getAll() {

        $con = DataBase::connect();
        $stmt = $con->prepare("SELECT * FROM categorias");
        $stmt -> execute();
        $result = $stmt -> get_result();

        $categorias = [];

        //guardar papelitos
        while ($categoria = $result -> fetch_object("Categoria")) {
            $categorias[] = $categoria;
        }

        $stmt->close();
        $con->close();

        return $categorias;
    }

    public static function getById($id) {

        $con = DataBase::connect();
        
        $stmt = $con->prepare("SELECT * FROM categorias WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $producto = $result->fetch_object("Categoria");

        $stmt->close();
        $con->close();

        return $producto ?: null;
    }

    public static function insertarCategoria($categoria) {
    //     $con = DataBase::connect();
    //     $stmt = $con->prepare("INSERT INTO camisetas (nombre, talla, precio) VALUES (?, ?, ?)");

    //     $nombre = $producto->getNombre();
    //     $talla = $producto->getTalla();
    //     $precio = $producto->getPrecio();

    //     $stmt->bind_param("ssd", $nombre, $talla, $precio);
    //     $stmt->execute();

    //     $stmt->close();
    //     $con->close();
    }
}
?>