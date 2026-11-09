<section class="sect pat">
    <h2>Nuestras Secciones</h2>
    <div class="tiles">
        <?php foreach ($categorias as $categoria): ?>
            <a class="tile" href="#">
                <img src="<?= url ?>img/carta-<?= htmlspecialchars($categoria->getSlug()) ?>.webp"
                     alt="<?= htmlspecialchars($categoria->getNombre()) ?>">
                <span><?= htmlspecialchars($categoria->getNombre()) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>