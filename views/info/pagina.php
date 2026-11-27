<section class="info-page-wrapper">
    <div class="info-page-header">
        <h1><?= htmlspecialchars($titulo) ?></h1>
        <p><?= htmlspecialchars($subtitulo) ?></p>
    </div>

    <div class="info-page-list">
        <?php foreach ($secciones as $sec): ?>
            <article class="info-page-card">
                <h2><?= htmlspecialchars($sec['titulo']) ?></h2>
                <p><?= htmlspecialchars($sec['texto']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>

    <div class="info-page-actions">
        <a href="<?= url ?>index.php" class="btn o">/ Volver al Inicio</a>
    </div>
</section>