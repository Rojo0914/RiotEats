<?php
include_once(__DIR__ . "/../bbdd/dataBase.php");
include_once(__DIR__ . "/Hamburguesa.php");
include_once(__DIR__ . "/Complemento.php");
include_once(__DIR__ . "/Postre.php");
include_once(__DIR__ . "/Bebida.php");
include_once(__DIR__ . "/Pack.php");

class ProductoDAO
{
    // Clase hija que corresponde a cada categoría (slug de la tabla categorias)
    private const CLASES = [
        'packs' => 'Pack',
        'complementos' => 'Complemento',
        'hamburguesas' => 'Hamburguesa',
        'postres' => 'Postre',
        'bebidas' => 'Bebida',
        'bebidas-con-alcohol' => 'Bebida',
    ];

    // Una categoría nueva (creada desde el formulario) todavía no tiene clase propia
    private const CLASE_POR_DEFECTO = 'Complemento';

    private static function clase($slugCategoria)
    {
        return self::CLASES[$slugCategoria] ?? self::CLASE_POR_DEFECTO;
    }

    // Productos activos de una categoría activa (por su slug)
    public static function getByCategoria($slug)
    {
        $con = DataBase::connect();
        $stmt = $con->prepare(
            "SELECT p.* FROM productos p
             JOIN categorias c ON c.id = p.categoria_id
             WHERE c.slug = ? AND c.activa = 1 AND p.activo = 1
             ORDER BY p.id"
        );
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $result = $stmt->get_result();

        $clase = self::clase($slug);
        $productos = [];
        while ($producto = $result->fetch_object($clase)) {
            $productos[] = $producto;
        }

        $stmt->close();
        $con->close();

        return $productos;
    }

    public static function getById($id)
    {
        $con = DataBase::connect();

        // 1) Averiguamos la categoría para saber qué clase hija instanciar
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

        // 2) Cargamos el producto en su clase
        $stmt = $con->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $producto = $stmt->get_result()->fetch_object(self::clase($fila['slug']));

        $stmt->close();
        $con->close();

        return $producto ?: null;
    }

    // Imagen del primer producto de cada categoría: se usa como portada de la tarjeta en la Carta
    public static function getPortadas()
    {
        $con = DataBase::connect();
        $result = $con->query(
            "SELECT c.slug,
                    (SELECT p.imagen FROM productos p
                     WHERE p.categoria_id = c.id AND p.activo = 1 AND p.imagen IS NOT NULL
                     ORDER BY p.id LIMIT 1) AS imagen
             FROM categorias c
             WHERE c.activa = 1"
        );

        $portadas = [];
        while ($fila = $result->fetch_assoc()) {
            $portadas[$fila['slug']] = $fila['imagen'];
        }

        $con->close();

        return $portadas;
    }
}
