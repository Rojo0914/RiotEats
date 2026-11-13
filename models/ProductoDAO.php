<?php
include_once(__DIR__ . "/../config/dataBase.php");
include_once(__DIR__ . "/Hamburguesa.php");
include_once(__DIR__ . "/Complemento.php");
include_once(__DIR__ . "/Postre.php");
include_once(__DIR__ . "/Bebida.php");
include_once(__DIR__ . "/Pack.php");

class ProductoDAO {
    private const CLASES = [
        'packs' => 'Pack',
        'complementos' => 'Complemento',
        'hamburguesas' => 'Hamburguesa',
        'postres' => 'Postre',
        'bebidas' => 'Bebida',
        'bebidas-con-alcohol' => 'Bebida',
    ];

    private const CLASE_POR_DEFECTO = 'Complemento';

    private static function clase($slugCategoria) {
        return self::CLASES[$slugCategoria] ?? self::CLASE_POR_DEFECTO;
    }

    // 1. Obtener los datos de la categoría por su ID (para el Hero y su slug)
    public static function getCategoriaById($id) {
        $con = DataBase::connect();
        $stmt = $con->prepare("SELECT * FROM categorias WHERE id = ? AND activa = 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $categoria = $stmt->get_result()->fetch_object();

        $stmt->close();
        $con->close();

        return $categoria ?: null;
    }

    // 2. Obtener los productos activos de esa categoría por ID
    public static function getByCategoria($categoriaId) {
        $con = DataBase::connect();
        $stmt = $con->prepare(
            "SELECT p.*, c.slug AS categoria_slug 
             FROM productos p
             JOIN categorias c ON c.id = p.categoria_id
             WHERE c.id = ? AND c.activa = 1 AND p.activo = 1
             ORDER BY p.id"
        );
        $stmt->bind_param("i", $categoriaId);
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        // Primero averiguamos el slug desde la primera fila o buscamos la categoría
        while ($fila = $result->fetch_assoc()) {
            $clase = self::clase($fila['categoria_slug']);
            break;
        }

        // Si hay resultados, rebobinamos el puntero y sacamos los objetos con su clase hija
        if ($result->num_rows > 0) {
            $result->data_seek(0);
            while ($producto = $result->fetch_object($clase)) {
                $productos[] = $producto;
            }
        }

        $stmt->close();
        $con->close();

        return $productos;
    }

    // 3. Obtener un producto individual por su ID
    public static function getById($id) {
        $con = DataBase::connect();
        $stmt = $con->prepare(
            "SELECT c.slug FROM productos p
             JOIN categorias c ON c.id = p.categoria_id
             WHERE p.id = ? AND p.activo = 1"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$fila) {
            $con->close();
            return null;
        }

        $stmt = $con->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $producto = $stmt->get_result()->fetch_object(self::clase($fila['slug']));

        $stmt->close();
        $con->close();

        return $producto ?: null;
    }

   public static function getExtrasActivos($productoId) {
    $con = DataBase::connect();
    $stmt = $con->prepare(
        "SELECT i.* 
         FROM ingredientes i
         JOIN producto_ingrediente pi ON pi.ingrediente_id = i.id
         WHERE pi.producto_id = ? AND i.activo = 1 AND i.precio_extra > 0 
         ORDER BY i.id ASC"
    );
    $stmt->bind_param("i", $productoId);
    $stmt->execute();
    $result = $stmt->get_result();

    $extras = [];
    while ($extra = $result->fetch_object()) {
        $extras[] = $extra;
    }

    $stmt->close();
    $con->close();

    return $extras;
}
}