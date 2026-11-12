<?php

// REVISAR EN CASA CAMBIAR A WEBP

$imagenesSecciones = [
    'Packs'               => 'img/Carta/Productos/Packs/Menú Noxus.png',
    'Complementos'        => 'img/Carta/Productos/Complementos/nachos.jpg',
    'Hamburguesas'        => 'img/Carta/Productos/Hamburguesa-Sola/zaun.png',
    'Postres'             => 'img/Carta/Productos/Postres/baron-cake.jpg',
    'Bebidas'             => 'img/Carta/Productos/Bebidas/Agua.png',
    'Bebidas con Alcohol' => 'img/Carta/Productos/Bebidas-con-alcohol/Cocktails/blue-buff.jpg',
];
$imagenPorDefecto = 'img/Carta/Tabla-Pizarra.png';
?>

<section class="sect pat">
    <h2>Nuestras Secciones</h2>
    <div class="tiles">
        <?php 
            foreach ($categorias as $categoria) { 
                $nombre = $categoria->getNombre();
                $img = $imagenesSecciones[$nombre] ?? $imagenPorDefecto;
            ?>
            <a class="tile" href="#">
                <img src="<?= url . htmlspecialchars($img) ?>"
                     alt="<?= htmlspecialchars($nombre) ?>">
                <span><?= htmlspecialchars($nombre) ?></span>
            </a>
        <?php 
            }
        ?>
    </div>
</section>