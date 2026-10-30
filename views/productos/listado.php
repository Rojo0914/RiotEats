<h1>Listado de Productos</h1>
<p><a href="?controller=producto&action=create">Crear producto</a></p>

<table>
    <tr>
        <th>Nombre</th>
        <th>Talla</th>
        <th>Precio</th>
        <th>Acciones</th>
    </tr>
    <?php if (empty($productos)) { ?>
        <tr>
            <td colspan="4">No hay productos todavía.</td>
        </tr>
    <?php } ?>
    <?php foreach ($productos as $producto) { ?>
        <tr>
            <td><?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($producto->getTalla(), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= number_format((float) $producto->getPrecio(), 2, ',', '.') ?> €</td>
            <td>
                <a href="?controller=producto&action=show&id=<?= (int) $producto->getId() ?>">Ver</a>
                <a href="?controller=producto&action=destroy&id=<?= (int) $producto->getId() ?>">Eliminar</a>
            </td>
        </tr>
    <?php } ?>
</table>
