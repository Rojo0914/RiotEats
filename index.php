<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hamburguesas</title>
    <!-- CSS de Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-dark text-white">

    <!-- Header / Navbar responsivo -->
    <header class="navbar navbar-expand-lg navbar-dark fixed-top bg-gradient-dark py-2 py-lg-3">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand me-3" href="#">
                <img src="" alt="">
                <div class="logo-placeholder bg-secondary rounded" style="width: 45px; height: 30px;"></div>
            </a>

            <!-- Botón hamburguesa para desplegar menú en móvil -->
            <button class="navbar-toggler border-0 p-1 me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Contenido colapsable (Menú + Acciones) -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <nav class="navbar-nav me-auto my-3 my-lg-0 gap-2 gap-lg-4 text-center text-lg-start">
                    <a class="nav-link text-white fw-semibold small" href="#">Carta</a>
                    <a class="nav-link text-white fw-semibold small" href="#">Promociones</a>
                    <a class="nav-link text-white fw-semibold small" href="#">Contáctanos</a>
                </nav>

                <div class="d-flex align-items-center justify-content-center justify-content-lg-end gap-3 pb-3 pb-lg-0">
                    <a href="#" class="text-white text-decoration-none">
                        <!-- Imagen / Icono: Globo terráqueo/idioma -->
                        <i class="bi bi-globe fs-5"></i>
                    </a>
                    <button class="btn btn-danger rounded-pill px-3 py-1 fw-bold small d-flex align-items-center gap-2">
                        <!-- Imagen / Icono: Carrito -->
                        <i class="bi bi-cart-fill"></i>
                        <span>Carrito</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Sección Hero / Banner Principal -->
    <section class="hero-section position-relative d-flex align-items-center pt-5">
        <!-- Imagen: Fondo del hero con la hamburguesa sobre tabla de madera -->
        <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
        <div class="container position-relative z-1 pt-5 text-center text-md-start">
            <h1 class="display-4 fw-bold">Hamburguesas</h1>
        </div>
    </section>

    <!-- Sección del Menú de Hamburguesas -->
    <main class="menu-section py-4 py-md-5 position-relative">
        <!-- Imagen: Textura/gráfico de fondo humo/sombras de la sección -->
        <div class="container">
            <h2 class="h1 fw-bold mb-4 text-center text-md-start">Hamburguesas</h2>

            <!-- Grid adaptable: 1 col en móvil (cols-1), 2 en tablet (sm-2), 4 en escritorio (md-4) -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3 g-md-4">
                
                <!-- Tarjeta 1 -->
                <div class="col">
                    <div class="card bg-dark text-white border-0 shadow-sm custom-card overflow-hidden">
                        <!-- Imagen: Foto de Hamburguesa Completa -->
                        <div class="card-bg-placeholder bg-secondary h-100 position-absolute w-100"></div>
                        <div class="card-img-overlay d-flex align-items-center justify-content-center text-center p-2">
                            <h5 class="card-title fw-bold text-shadow m-0">Hamburguesa Completa</h5>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 2 -->
                <div class="col">
                    <div class="card bg-dark text-white border-0 shadow-sm custom-card overflow-hidden">
                        <!-- Imagen: Foto de Hamburguesa Noxus -->
                        <div class="card-bg-placeholder bg-secondary h-100 position-absolute w-100"></div>
                        <div class="card-img-overlay d-flex align-items-center justify-content-center text-center p-2">
                            <h5 class="card-title fw-bold text-shadow m-0">Hamburguesa Noxus</h5>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 3 -->
                <div class="col">
                    <div class="card bg-dark text-white border-0 shadow-sm custom-card overflow-hidden">
                        <!-- Imagen: Foto de Hamburguesa Zaun -->
                        <div class="card-bg-placeholder bg-secondary h-100 position-absolute w-100"></div>
                        <div class="card-img-overlay d-flex align-items-center justify-content-center text-center p-2">
                            <h5 class="card-title fw-bold text-shadow m-0">Hamburguesa Zaun</h5>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 4 -->
                <div class="col">
                    <div class="card bg-dark text-white border-0 shadow-sm custom-card overflow-hidden">
                        <!-- Imagen: Foto de Hamburguesa Piltover -->
                        <div class="card-bg-placeholder bg-secondary h-100 position-absolute w-100"></div>
                        <div class="card-img-overlay d-flex align-items-center justify-content-center text-center p-2">
                            <h5 class="card-title fw-bold text-shadow m-0">Hamburguesa Piltover</h5>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 5 -->
                <div class="col">
                    <div class="card bg-dark text-white border-0 shadow-sm custom-card overflow-hidden">
                        <!-- Imagen: Foto de Hamburguesa Bind -->
                        <div class="card-bg-placeholder bg-secondary h-100 position-absolute w-100"></div>
                        <div class="card-img-overlay d-flex align-items-center justify-content-center text-center p-2">
                            <h5 class="card-title fw-bold text-shadow m-0">Hamburguesa Bind</h5>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 6 -->
                <div class="col">
                    <div class="card bg-dark text-white border-0 shadow-sm custom-card overflow-hidden">
                        <!-- Imagen: Foto de Hamburguesa Templo Jonia -->
                        <div class="card-bg-placeholder bg-secondary h-100 position-absolute w-100"></div>
                        <div class="card-img-overlay d-flex align-items-center justify-content-center text-center p-2">
                            <h5 class="card-title fw-bold text-shadow m-0">Hamburguesa Templo Jonia</h5>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 7 -->
                <div class="col">
                    <div class="card bg-dark text-white border-0 shadow-sm custom-card overflow-hidden">
                        <!-- Imagen: Foto de Hamburguesa Pengu -->
                        <div class="card-bg-placeholder bg-secondary h-100 position-absolute w-100"></div>
                        <div class="card-img-overlay d-flex align-items-center justify-content-center text-center p-2">
                            <h5 class="card-title fw-bold text-shadow m-0">Hamburguesa Pengu</h5>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 8 -->
                <div class="col">
                    <div class="card bg-dark text-white border-0 shadow-sm custom-card overflow-hidden">
                        <!-- Imagen: Foto de Hamburguesa Alistar -->
                        <div class="card-bg-placeholder bg-secondary h-100 position-absolute w-100"></div>
                        <div class="card-img-overlay d-flex align-items-center justify-content-center text-center p-2">
                            <h5 class="card-title fw-bold text-shadow m-0">Hamburguesa Alistar</h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Footer Adaptativo -->
    <footer class="bg-black text-secondary py-4 border-top border-dark">
        <div class="container">
            <div class="row align-items-center gy-4 text-center text-md-start">
                
                <!-- Logo Footer -->
                <div class="col-12 col-md-2 d-flex justify-content-center justify-content-md-start">
                    <!-- Imagen: Logo blanco del footer -->
                    <div class="bg-secondary rounded" style="width: 70px; height: 35px;"></div>
                </div>
                
                <!-- Enlaces del Footer -->
                <div class="col-12 col-md-8">
                    <div class="d-flex flex-wrap gap-2 gap-md-3 justify-content-center justify-content-md-start small text-uppercase fw-bold footer-links">
                        <a href="#" class="text-secondary text-decoration-none">PRENSA</a>
                        <a href="#" class="text-secondary text-decoration-none">SEGURIDAD</a>
                        <a href="#" class="text-secondary text-decoration-none">LEGAL</a>
                        <a href="#" class="text-secondary text-decoration-none">AVISO DE PRIVACIDAD</a>
                        <a href="#" class="text-secondary text-decoration-none">TÉRMINOS DE SERVICIO</a>
                        <a href="#" class="text-secondary text-decoration-none">ASISTENCIA AL CLIENTE</a>
                        <a href="#" class="text-secondary text-decoration-none">VERIFICAR EN LÍNEA</a>
                        <a href="#" class="text-secondary text-decoration-none">ACCESIBILIDAD</a>
                        <a href="#" class="text-secondary text-decoration-none">INFORMES ANUALES</a>
                    </div>
                </div>

                <!-- Iconos Sociales -->
                <div class="col-12 col-md-2 d-flex justify-content-center justify-content