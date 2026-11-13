<?php
include_once(__DIR__ . "/ProductoDAO.php");

class Carrito
{
    const MAX_UNIDADES = 20;

    public static function agregar($idProducto, $cantidad)
    {
        $idProducto = (int) $idProducto;
        $actual = $_SESSION['carrito'][$idProducto] ?? 0;
        $_SESSION['carrito'][$idProducto] = min(self::MAX_UNIDADES, $actual + (int) $cantidad);
    }

    public static function quitar($idProducto)
    {
        unset($_SESSION['carrito'][(int) $idProducto]);
    }

    public static function vaciar()
    {
        $_SESSION['carrito'] = [];
    }

    public static function contar()
    {
        return array_sum($_SESSION['carrito'] ?? []);
    }

    // Devuelve [['producto' => Producto, 'cantidad' => n, 'subtotal' => x], ...]
    public static function lineas()
    {
        $lineas = [];
        foreach ($_SESSION['carrito'] ?? [] as $id => $cantidad) {
            $producto = ProductoDAO::getById($id);
            if ($producto === null) { // el producto ya no existe o está desactivado
                unset($_SESSION['carrito'][$id]);
                continue;
            }
            $lineas[] = [
                'producto' => $producto,
                'cantidad' => $cantidad,
                'subtotal' => (float) $producto->getPrecio() * $cantidad,
            ];
        }
        return $lineas;
    }

    public static function total($lineas)
    {
        return array_sum(array_column($lineas, 'subtotal'));
    }
}