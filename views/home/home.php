<!-- 1. HERO SUPERIOR -->
<section class="home-hero">
    <!-- <video autoplay muted loop playsinline class="hero-video">
        <source src="<?= url ?>video/hero-riot-eats.mp4" type="video/mp4">
    </video> -->
    <div class="home-hero-overlay"></div>
</section>

<!-- 2. SECCIÓN PROMOCIONES -->
<section class="home-promociones">
    <div class="home-container home-promociones-grid">

        <!-- Columna izquierda: Título, subtítulo y primer pack destacado -->
        <div class="promo-left">
            <h2 class="home-section-title">Promociones</h2>
            <p class="promo-subtitle">Mira nuestras mejores promociones //<br>para comer solo o acompañado</p>

            <?php if (!empty($packs)):
                $packDestacado = $packs[0]; ?>
                <a href="?controller=producto&action=detalle&id=<?= $packDestacado->getId() ?>" class="promo-featured-card">
                    <img src="<?= htmlspecialchars($packDestacado->getImagen()) ?>"
                        alt="<?= htmlspecialchars($packDestacado->getNombre()) ?>">
                </a>
            <?php endif; ?>
        </div>

        <!-- Columna derecha: Los otros 4 packs apilados -->
        <div class="promo-right">
            <?php
            $otrosPacks = array_slice($packs, 1, 4);
            foreach ($otrosPacks as $pack): ?>
                <a href="?controller=producto&action=detalle&id=<?= $pack->getId() ?>" class="promo-mini-card">
                    <div class="promo-mini-info">
                        <h3><?= htmlspecialchars($pack->getNombre()) ?> //</h3>
                        <span><?= htmlspecialchars($pack->getDescripcion() ?? 'Combo especial') ?></span>
                    </div>
                    <div class="promo-mini-img">
                        <img src="<?= htmlspecialchars($pack->getImagen()) ?>"
                            alt="<?= htmlspecialchars($pack->getNombre()) ?>">
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- 3. SECCIÓN COCKTAIL -->
<section class="home-cocktail">
    <div class="home-container">
        <h2 class="home-section-title">Cocktail</h2>
    </div>

    <div class="cocktail-track">
        <?php if (!empty($cock)): ?>
            <?php foreach ($cock as $bebida): ?>
                <a href="?controller=producto&action=detalle&id=<?= $bebida->getId() ?>" class="cocktail-card">
                    <div class="cocktail-card-inner">
                        <img src="<?= htmlspecialchars($bebida->getImagen()) ?>"
                            alt="<?= htmlspecialchars($bebida->getNombre()) ?>">
                        <div class="cocktail-card-gradient"></div>
                        <span class="cocktail-name"><?= htmlspecialchars($bebida->getNombre()) ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- 4. SECCIÓN CONOCE MÁS -->
<section class="home-conoce-mas">
    <div class="home-container">
        <h2 class="home-section-title">Conoce más</h2>

        <div class="categorias-grid">
            <a href="?controller=carta&categoria=hamburguesas" class="categoria-skew-card">
                <div class="categoria-skew-inner">
                    <img src="assets/img/cat-hamburguesas.jpg" alt="Hamburguesas">
                    <div class="categoria-overlay"></div>
                    <span>Hamburguesas</span>
                </div>
            </a>

            <a href="?controller=carta&categoria=complementos" class="categoria-skew-card">
                <div class="categoria-skew-inner">
                    <img src="assets/img/cat-complementos.jpg" alt="Complementos">
                    <div class="categoria-overlay"></div>
                    <span>Complementos</span>
                </div>
            </a>

            <a href="?controller=carta&categoria=postres" class="categoria-skew-card">
                <div class="categoria-skew-inner">
                    <img src="assets/img/cat-postres.jpg" alt="Postres">
                    <div class="categoria-overlay"></div>
                    <span>Postres</span>
                </div>
            </a>

            <a href="?controller=carta&categoria=bebidas" class="categoria-skew-card">
                <div class="categoria-skew-inner">
                    <img src="assets/img/cat-bebidas.jpg" alt="Bebidas">
                    <div class="categoria-overlay"></div>
                    <span>Bebidas</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- 5. BANNER ¡BUSCAMOS GENTE! -->
<section class="home-buscamos">
    <div class="buscamos-left">
        <div class="buscamos-content">
            <h2>¡Buscamos gente!</h2>
            <p>Uníos a Riot Eats para afilar vuestro cuchillo y crear recetas experiencias para los clientes.</p>
            <a href="?controller=contacto" class="btn-solicitar">¡Solicitar!</a>
        </div>
    </div>
    <div class="buscamos-right">
        <img src="assets/img/gong-buscamos.jpg" alt="Te esperamos en Riot Eats">
    </div>
</section>