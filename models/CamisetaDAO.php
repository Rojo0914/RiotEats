<?php
include_once("config/dataBase.php");
include_once("models/Camiseta.php");

class CamisetaDAO
{
    public static function getAll()
    {
        $con = DataBase::connect();
        $stmt = $con->prepare("SELECT * FROM camisetas");
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        while ($producto = $result->fetch_object("Camiseta")) {
            $productos[] = $producto;
        }

        $stmt->close();
        $con->close();

        return $productos;
    }

    public static function getById($id)
    {
        $con = DataBase::connect();
        $stmt = $con->prepare("SELECT * FROM camisetas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $producto = $result->fetch_object("Camiseta");

        $stmt->close();
        $con->close();

        return $producto ?: null;
    }

    public static function store($producto)
    {
        $con = DataBase::connect();
        $stmt = $con->prepare("INSERT INTO camisetas (nombre, talla, precio) VALUES (?, ?, ?)");

        $nombre = $producto->getNombre();
        $talla = $producto->getTalla();
        $precio = $producto->getPrecio();

        $stmt->bind_param("ssd", $nombre, $talla, $precio);
        $stmt->execute();

        $stmt->close();
        $con->close();
    }

    public static function destroy($id)
    {
        $con = DataBase::connect();
        $stmt = $con->prepare("DELETE FROM camisetas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $stmt->close();
        $con->close();
    }
}
