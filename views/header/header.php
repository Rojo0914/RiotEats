<nav class="nav">
    <a href="<?= url ?>?controller=home&action=index">
        <img class="logo" src="<?= url ?>img/Logo/Logo-blanco.svg" alt="Riot Eats">
    </a>
    
    <a href="<?= url ?>?controller=categoria&action=index">Carta</a>
    <a href="<?= url ?>?controller=promociones&action=index">Promociones</a>
    <a href="<?= url ?>index.php?controller=contacto&action=index">Contáctanos</a>
    <img src="<?= url ?>img/svg/World.svg" alt="Idioma">
    
    <a class="btn s" href="<?= url ?>?controller=carrito&action=index">
        <img src="<?= url ?>img/svg/Carrito.svg" alt="" width="24" height="24">
        Carrito
    </a>


    <!-- APARTADO DE PERFIL DE USUARIO -->
    <?php if (isset($_SESSION['usuario'])): ?>
        <div class="perfil-menu">
            <button type="button" class="perfil-btn">
                <img src="<?= url ?>img/svg/Usuario.svg" alt="Perfil" width="22" height="22">
                <span><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></span>
            </button>

            <div class="perfil-dropdown">
                <?php if ($_SESSION['usuario']['rol'] == 'admin'): ?>
                    <a href="<?= url ?>?controller=admin&action=index">Panel Admin</a>
                <?php endif; ?>
                
                <a href="<?= url ?>?controller=usuario&action=logout" class="logout-link">Cerrar sesión</a>
            </div>
        </div>
    <?php else: ?>
        <a href="<?= url ?>?controller=usuario&action=login" class="perfil-btn">
            <img src="<?= url ?>img/svg/Usuario.svg" alt="Iniciar sesión" width="22" height="22">
            <span>Entrar</span>
        </a>
    <?php endif; ?>
</nav>