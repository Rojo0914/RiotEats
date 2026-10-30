<h1>Detalle del producto</h1>
<p><a href="?controller=producto">Volver al listado</a></p>

<p><strong>Nombre:</strong> <?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></p>
<p><strong>Talla:</strong> <?= htmlspecialchars($producto->getTalla(), ENT_QUOTES, 'UTF-8') ?></p>
<p><strong>Precio:</strong> <?= number_format((float) $producto->getPrecio(), 2, ',', '.') ?> €</p>

<p>
    <a href="?controller=producto&action=destroy&id=<?= (int) $producto->getId() ?>">Eliminar</a>
</p>
