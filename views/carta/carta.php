<section class="hero">
    <img class="bg" src="<?=url ?>/img/Carta/Carta.png" alt="Carta">
    <h1>Carta</h1>
</section>

<section class="sect pat">
    <h2>Nuestras Secciones</h2>
    <div class="tiles">
        <?php 
            foreach ($categorias as $categoria) { 
                $nombre = $categoria->getNombre();
            ?>
            <a class="tile" href="#">
                <img src="<?= url . htmlspecialchars($categoria->getImagen()) ?>"
                     alt="<?= htmlspecialchars($nombre) ?>">
                <span><?= htmlspecialchars($nombre) ?></span>
            </a>
        <?php 
            }
        ?>
    </div>
</section>