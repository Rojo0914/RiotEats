<?php 
    
    // creamos el array de las categorías de la bbdd si no está creada
    if (!isset($categorias)) {
        $categorias = [];
    }
?>

// VISTA DE LA CARTA

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta - Riot Eats</title>
    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Estilos apuntando a la raíz -->
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>

    <!-- HEADER / NAVBAR -->
    <header class="hero-header">
        <nav class="navbar">
            <a href="../../index.php" class="logo">
                <i class="fa-solid fa-hand-fist"></i> RIOT EATS
            </a>
            <ul class="nav-links">
                <li><a href="../../index.php?controller=categoria&action=index" class="active">Carta</a></li>
                <li><a href="#">Promociones</a></li>
                <li><a href="#">Contáctanos</a></li>
            </ul>
            <div class="nav-actions">
                <span class="lang-select"><i class="fa-solid fa-globe"></i></span>
                <a href="#" class="btn-cart">
                    <i class="fa-solid fa-cart-shopping"></i> Carrito
                </a>
            </div>
        </nav>
        <h1 class="hero-title">Carta</h1>
    </header>

    <!-- SECCIÓN PRINCIPAL: NUESTRAS SECCIONES (CATEGORÍAS) -->
    <main class="main-container">
        <h2 class="section-title">Nuestras Secciones</h2>

        <div class="grid-cards">
            <!-- Tarjeta 1: Packs -->
            <a href="../../index.php?controller=producto&action=categoria&slug=packs" class="card-item">
                <img src="../../img/categorias/packs.webp" alt="Packs" class="card-bg" onerror="this.src='https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=600&q=80';">
                <div class="card-overlay"></div>
                <span class="card-title">Packs</span>
            </a>

            <!-- Tarjeta 2: Complementos -->
            <a href="../../index.php?controller=producto&action=categoria&slug=complementos" class="card-item">
                <img src="../../img/categorias/complementos.webp" alt="Complementos" class="card-bg" onerror="this.src='https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=600&q=80';">
                <div class="card-overlay"></div>
                <span class="card-title">Complementos</span>
            </a>

            <!-- Tarjeta 3: Hamburguesas -->
            <a href="../../index.php?controller=producto&action=categoria&slug=hamburguesas" class="card-item">
                <img src="../../img/categorias/hamburguesas.webp" alt="Hamburguesas" class="card-bg" onerror="this.src='https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=600&q=80';">
                <div class="card-overlay"></div>
                <span class="card-title">Hamburguesas</span>
            </a>

            <!-- Tarjeta 4: Postres -->
            <a href="../../index.php?controller=producto&action=categoria&slug=postres" class="card-item">
                <img src="../../img/categorias/postres.webp" alt="Postres" class="card-bg" onerror="this.src='https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=600&q=80';">
                <div class="card-overlay"></div>
                <span class="card-title">Postres</span>
            </a>

            <!-- Tarjeta 5: Cocktails -->
            <a href="../../index.php?controller=producto&action=categoria&slug=bebidas-con-alcohol" class="card-item">
                <img src="../../img/categorias/bebidas-con-alcohol.webp" alt="Cocktails" class="card-bg" onerror="this.src='https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=600&q=80';">
                <div class="card-overlay"></div>
                <span class="card-title">Cocktails</span>
            </a>

            <!-- Tarjeta 6: Bebidas -->
            <a href="../../index.php?controller=producto&action=categoria&slug=bebidas" class="card-item">
                <img src="../../img/categorias/bebidas.webp" alt="Bebidas" class="card-bg" onerror="this.src='https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=600&q=80';">
                <div class="card-overlay"></div>
                <span class="card-title">Bebidas</span>
            </a>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <div class="footer-container">
            <div class="footer-top">
                <div class="footer-brand">
                    <i class="fa-solid fa-hand-fist"></i> RIOT EATS
                </div>
                <ul class="footer-links">
                    <li><a href="#">Física</a></li>
                    <li><a href="#">Solución</a></li>
                    <li><a href="#">Legal</a></li>
                    <li><a href="#">Aviso de Privacidad</a></li>
                    <li><a href="#">Términos de Servicio</a></li>
                    <li><a href="#">Atención al Cliente</a></li>
                    <li><a href="#">Política de Cookies</a></li>
                </ul>
                <div class="footer-socials">
                    <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-discord"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                </div>
            </div>
            <div class="footer-bottom">
                <p><a href="#">Preferencias de cookies</a> | © 2026 Riot Eats, Inc. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

</body>
</html>