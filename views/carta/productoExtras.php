<section class="hero hero-detalle">
    <img class="bg" 
         src="<?= url . htmlspecialchars($producto->getImagen()) ?>" 
         alt="<?= htmlspecialchars($producto->getNombre()) ?>">
    
    <div class="hero-content">
        <h1><?= htmlspecialchars($producto->getNombre()) ?></h1>
        <p class="desc"><?= nl2br(htmlspecialchars($producto->getDescripcion())) ?></p>
        
        <div class="hero-actions">  
            <span class="btn xl price-tag" id="precio-total" data-base="<?= $producto->getPrecio() ?>">
                / <?= number_format($producto->getPrecio(), 2) ?> €
            </span>
            
            
            <?php if (!empty($extras)): ?>
                <a href="#seccion-extras" class="btn o">
                    / Añadir Extras
                </a>
                <?php endif; ?>
            <button type="submit" form="form-producto" class="btn o">
                / Añadir al Carrito
            </button>
        </div>
    </div>
</section>

<form id="form-producto" action="<?= url ?>index.php?controller=carrito&action=add" method="POST">
    <input type="hidden" name="producto_id" value="<?= $producto->getId() ?>">
    
    <?php if (!empty($extras)): ?>
        <section id="seccion-extras" class="sect b pat">
            <h2>Añade tus extras</h2>
            
            <div class="tiles burgers extras-grid">
                <?php foreach ($extras as $extra): 
                $idExtra     = $extra->id;
                $precioExtra = $extra->precio_extra;
                ?>
                <div class="tile extra-card" data-precio="<?= $precioExtra ?>">
                    <img src="<?= url . htmlspecialchars($extra->imagen) ?>" 
                         alt="<?= htmlspecialchars($extra->nombre) ?>">
                    
                    <small class="extra-price">+ <?= number_format($precioExtra, 2) ?>€</small>
                    <span><?= htmlspecialchars($extra->nombre) ?></span>
                    
                    <div class="extra-controls">
                        <button type="button" class="btn-qty minus" data-id="<?= $idExtra ?>" data-delta="-1">−</button>
                        <strong id="qty-display-<?= $idExtra ?>">0</strong>
                        <button type="button" class="btn-qty plus" data-id="<?= $idExtra ?>" data-delta="1">+</button>
                        <input type="hidden" name="extras[<?= $idExtra ?>]" id="input-extra-<?= $idExtra ?>" value="0">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</form>