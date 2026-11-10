<?php
// Variables que llegan desde productoController::categoria():
// $categoria (objeto Categoria) y $productos (objetos hijos de Producto)
?>
<section class="seccion">
    <a class="volver" href="<?= url ?>index.php?controller=categoria">← Volver a la carta</a>

    <?php if ($categoria->getDescripcion()) { ?>
        <p class="subtitulo"><?= htmlspecialchars($categoria->getDescripcion(), ENT_QUOTES, 'UTF-8') ?></p>
    <?php } ?>

    <?php if (empty($productos)) { ?>
        <p class="vacio">No hay productos en esta sección todavía.</p>
    <?php } ?>

    <div class="productos">
        <?php foreach ($productos as $producto) { ?>
            <article class="producto">
                <?php if ($producto->getImagen()) { ?>
                    <img src="<?= url . htmlspecialchars($producto->getImagen(), ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?>">
                <?php } else { ?>
                    <div class="sin-imagen"></div>
                <?php } ?>

                <div class="info">
                    <h3><?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></h3>
                    <p><?= htmlspecialchars((string) $producto->getDescripcion(), ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="precio"><?= number_format((float) $producto->getPrecio(), 2, ',', '.') ?> €</p>

                    <form class="anadir" action="<?= url ?>index.php?controller=carrito&action=agregar" method="POST">
                        <input type="hidden" name="producto_id" value="<?= (int) $producto->getId() ?>">
                        <input type="hidden" name="volver" value="<?= htmlspecialchars($categoria->getSlug(), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="number" name="cantidad" value="1" min="1" max="20" aria-label="Cantidad">
                        <button class="btn" type="submit">/ Añadir al carrito</button>
                    </form>
                </div>
            </article>
        <?php } ?>
    </div>
</section>
