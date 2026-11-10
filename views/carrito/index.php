<?php
// Variables que llegan desde carritoController::index(): $lineas y $total
?>
<section class="seccion">
    <a class="volver" href="<?= url ?>index.php?controller=categoria">← Seguir viendo la carta</a>

    <?php if (empty($lineas)) { ?>
        <p class="vacio">Tu carrito está vacío.</p>
    <?php } else { ?>
        <div class="carrito">
            <?php foreach ($lineas as $linea) { ?>
                <?php $producto = $linea['producto']; ?>
                <div class="linea">
                    <?php if ($producto->getImagen()) { ?>
                        <img src="<?= url . htmlspecialchars($producto->getImagen(), ENT_QUOTES, 'UTF-8') ?>" alt="">
                    <?php } else { ?>
                        <div class="sin-imagen"></div>
                    <?php } ?>
                    <span><?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></span>
                    <span>× <?= (int) $linea['cantidad'] ?></span>
                    <span><?= number_format($linea['subtotal'], 2, ',', '.') ?> €</span>
                    <form action="<?= url ?>index.php?controller=carrito&action=quitar" method="POST">
                        <input type="hidden" name="producto_id" value="<?= (int) $producto->getId() ?>">
                        <button class="btn-texto" type="submit">Quitar</button>
                    </form>
                </div>
            <?php } ?>
        </div>

        <div class="total">
            <span>Total</span>
            <strong><?= number_format($total, 2, ',', '.') ?> €</strong>
        </div>

        <form class="acciones" action="<?= url ?>index.php?controller=carrito&action=vaciar" method="POST">
            <button class="btn borde" type="submit">/ Vaciar carrito</button>
        </form>
    <?php } ?>
</section>
