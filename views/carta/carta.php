<section class="hero">
    <img class="bg" src="<?=url ?>/img/Carta/Carta.webp" alt="Carta">
    <h1>Carta</h1>
</section>

<section class="sect pat">
    <h2>Nuestras Secciones</h2>
    <div class="tiles">
        <?php 
            foreach ($categorias as $categoria) {
                $id =$categoria->getId();
                $nombre = $categoria->getNombre();
            ?>
            <a class="tile" href="<?= url ?>index.php?controller=producto&action=categoria&id=<?= $id ?>">
                <img src="<?= url . htmlspecialchars($categoria->getImagen()) ?>"
                     alt="<?= htmlspecialchars($nombre) ?>">
                <span><?= htmlspecialchars($nombre) ?></span>
            </a>
        <?php 
            }
        ?>
    </div>
</section>