<section class="hero">
    <img class="bg" 
         src="<?= url . htmlspecialchars($categoria->imagen) ?>" 
         alt="<?= htmlspecialchars($categoria->nombre) ?>">
    <h1><?= htmlspecialchars($categoria->nombre) ?></h1>
</section>

<section class="sect b pat">
    <h2><?= htmlspecialchars($categoria->nombre) ?></h2>

    <div class="tiles burgers">
        <?php foreach ($productos as $producto): ?>
            <a class="tile" href="<?= url ?>index.php?controller=producto&action=detalle&id=<?= $producto->getId() ?>">
                <img src="<?= url . htmlspecialchars($producto->getImagen()) ?>" 
                     alt="<?= htmlspecialchars($producto->getNombre()) ?>">
                <span><?= htmlspecialchars($producto->getNombre()) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>