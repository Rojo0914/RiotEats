<section class="sect pat">
    <h2>Nuestras Secciones</h2>
    <div class="tiles">
        <?php foreach ($categorias as $categoria): ?>
            <a class="tile" href="#">

                <!-- CAMBIAR LAS IMÁGENES -->
                <img src="<?= url ?>img/Carta/Productos/Bebidas/Agua.png"
                     alt="<?= htmlspecialchars($categoria->getNombre()) ?>">
                <span><?= htmlspecialchars($categoria->getNombre()) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>