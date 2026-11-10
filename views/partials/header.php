<header class="hero">
    <img src="<?= url ?>img/Carta/Carta.webp" alt="">
    <nav class="nav">
        <a class="logo" href="<?= url ?>index.php?controller=categoria">
            <img src="<?= url ?>img/Logo/Logo-Blanco.webp" alt="Riot Eats" width="132" height="116">
        </a>
        <a href="<?= url ?>index.php?controller=categoria">Carta</a>
        <a href="#">Promociones</a>
        <a href="#contacto">Contáctanos</a>
        <img class="mundo" src="<?= url ?>img/svg/World.svg" alt="Idioma" width="24" height="24">
        <a class="btn-carrito" href="<?= url ?>index.php?controller=carrito">
            <img src="<?= url ?>img/svg/Carrito.svg" alt="" width="24" height="24">
            Carrito<?= $cantidadCarrito > 0 ? " (" . (int) $cantidadCarrito . ")" : "" ?>
        </a>
    </nav>
    <h1><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></h1>
</header>
