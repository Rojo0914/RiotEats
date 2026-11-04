-- ============================================================
-- RIOT EATS - BASE DE DATOS ACTUALIZADA Y COMPLETA
-- MYSQL 8.4
-- ============================================================

CREATE DATABASE IF NOT EXISTS riot_eats
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE riot_eats;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 0. ELIMINAR TABLAS EXISTENTES
-- ============================================================

SET SESSION group_concat_max_len = 1000000;

-- ============================================================
-- 23. CONSULTAS DE COMPROBACIÓN (SELECT * DE CADA TABLA)
-- ============================================================

SELECT * FROM usuarios;
SELECT * FROM categorias;
SELECT * FROM productos ORDER BY id ASC;
SELECT * FROM ingredientes;
SELECT * FROM producto_ingrediente;
SELECT * FROM producto_combo;
SELECT * FROM ofertas;
SELECT * FROM oferta_producto;
SELECT * FROM pedidos;
SELECT * FROM pedido_detalle;
SELECT * FROM pedido_detalle_ingrediente;

SELECT GROUP_CONCAT(
    CONCAT('`', REPLACE(table_name,'`','``'), '`')
    SEPARATOR ','
)
INTO @tablas
FROM information_schema.tables
WHERE table_schema = DATABASE()
AND table_type = 'BASE TABLE';

SET @sql = IF(
    @tablas IS NULL,
    'SELECT 1',
    CONCAT('DROP TABLE ', @tablas)
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- 1. USUARIOS
-- ============================================================

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    fecha_nacimiento DATE,
    rol VARCHAR(20) NOT NULL DEFAULT 'usuario',
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CHECK (rol IN ('usuario','admin'))
) ENGINE=InnoDB;

-- ============================================================
-- 2. CATEGORIAS
-- ============================================================

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(150) UNIQUE NOT NULL,
    descripcion TEXT,
    activa BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB;

-- ============================================================
-- 3. PRODUCTOS
-- ============================================================

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    sku VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(150) UNIQUE NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(255),
    activo BOOLEAN NOT NULL DEFAULT TRUE,

    CONSTRAINT fk_productos_categoria
    FOREIGN KEY (categoria_id)
    REFERENCES categorias(id),

    CHECK (precio >= 0)
) ENGINE=InnoDB;

-- ============================================================
-- 4. INGREDIENTES
-- ============================================================

CREATE TABLE ingredientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(100) UNIQUE NOT NULL,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    precio_extra DECIMAL(10,2) NOT NULL DEFAULT 0,
    activo BOOLEAN NOT NULL DEFAULT TRUE,

    CHECK (precio_extra >= 0)
) ENGINE=InnoDB;

-- ============================================================
-- 5. RELACION PRODUCTO - INGREDIENTE (RECETAS Y COMPONENTES)
-- ============================================================

CREATE TABLE producto_ingrediente (
    producto_id INT NOT NULL,
    ingrediente_id INT NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL DEFAULT 1,

    PRIMARY KEY (producto_id, ingrediente_id),

    FOREIGN KEY (producto_id)
    REFERENCES productos(id),

    FOREIGN KEY (ingrediente_id)
    REFERENCES ingredientes(id),

    CHECK (cantidad > 0)
) ENGINE=InnoDB;

-- ============================================================
-- 6. COMPONENTES DE LOS COMBOS / PACKS
-- ============================================================

CREATE TABLE producto_combo (
    combo_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,

    PRIMARY KEY (combo_id, producto_id),

    FOREIGN KEY (combo_id)
    REFERENCES productos(id),

    FOREIGN KEY (producto_id)
    REFERENCES productos(id),

    CHECK (cantidad > 0),
    CHECK (combo_id <> producto_id)
) ENGINE=InnoDB;

-- ============================================================
-- 7. OFERTAS
-- ============================================================

CREATE TABLE ofertas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    tipo VARCHAR(20) NOT NULL,
    valor DECIMAL(10,2),
    codigo VARCHAR(50) UNIQUE,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME,
    activa BOOLEAN NOT NULL DEFAULT TRUE,

    CHECK (tipo IN ('descuento','2x1','combo','codigo')),
    CHECK (valor IS NULL OR valor >= 0),
    CHECK (fecha_fin IS NULL OR fecha_fin >= fecha_inicio)
) ENGINE=InnoDB;

-- ============================================================
-- 8. RELACION OFERTA - PRODUCTO
-- ============================================================

CREATE TABLE oferta_producto (
    oferta_id INT NOT NULL,
    producto_id INT NOT NULL,

    PRIMARY KEY (oferta_id, producto_id),

    FOREIGN KEY (oferta_id)
    REFERENCES ofertas(id),

    FOREIGN KEY (producto_id)
    REFERENCES productos(id)
) ENGINE=InnoDB;

-- ============================================================
-- 9. PEDIDOS
-- ============================================================

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'pendiente',
    fecha_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(10,2) NOT NULL,
    descuento DECIMAL(10,2) NOT NULL DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    metodo_pago VARCHAR(50) NOT NULL DEFAULT 'simulado',
    estado_pago VARCHAR(20) NOT NULL DEFAULT 'pendiente',
    oferta_id INT NULL,
    codigo_oferta VARCHAR(50) NULL,
    referencia_prueba VARCHAR(50) UNIQUE,

    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (oferta_id) REFERENCES ofertas(id),

    CHECK (estado IN ('pendiente','confirmado','preparando','enviado','entregado','cancelado')),
    CHECK (estado_pago IN ('pendiente','pagado','fallido')),
    CHECK (subtotal >= 0),
    CHECK (descuento >= 0),
    CHECK (descuento <= subtotal),
    CHECK (total >= 0),
    CHECK (total = subtotal - descuento)
) ENGINE=InnoDB;

-- ============================================================
-- 10. DETALLES DE PEDIDOS
-- ============================================================

CREATE TABLE pedido_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NULL,
    nombre_producto VARCHAR(150) NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    cantidad INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    referencia_prueba VARCHAR(60) UNIQUE,

    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL,

    CHECK (cantidad > 0),
    CHECK (precio_unitario >= 0),
    CHECK (subtotal >= 0)
) ENGINE=InnoDB;

-- ============================================================
-- 11. HISTORIAL DE INGREDIENTES DE PEDIDOS
-- ============================================================

CREATE TABLE pedido_detalle_ingrediente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_detalle_id INT NOT NULL,
    ingrediente_id INT NULL,
    nombre_ingrediente VARCHAR(100) NOT NULL,
    precio_extra DECIMAL(10,2) NOT NULL DEFAULT 0,
    accion VARCHAR(20) NOT NULL,

    FOREIGN KEY (pedido_detalle_id) REFERENCES pedido_detalle(id),
    FOREIGN KEY (ingrediente_id) REFERENCES ingredientes(id) ON DELETE SET NULL,

    CHECK (precio_extra >= 0),
    CHECK (accion IN ('incluido','quitado','añadido'))
) ENGINE=InnoDB;

-- ============================================================
-- 12. INSERTAR CATEGORIAS
-- ============================================================

INSERT INTO categorias (nombre, slug, descripcion, activa) VALUES
('Packs', 'packs', 'Combos y paquetes especiales', TRUE),
('Complementos', 'complementos', 'Entrantes y acompañamientos', TRUE),
('Hamburguesas', 'hamburguesas', 'Hamburguesas temáticas de Riot Games', TRUE),
('Postres', 'postres', 'Postres dulces', TRUE),
('Bebidas', 'bebidas', 'Refrescos y bebidas no alcohólicas', TRUE),
('Bebidas con Alcohol', 'bebidas-con-alcohol', 'Cócteles y bebidas alcohólicas temáticas', TRUE);

-- ============================================================
-- 13. INSERTAR PRODUCTOS
-- ============================================================

INSERT INTO productos (categoria_id, sku, slug, nombre, descripcion, precio, imagen, activo)
SELECT c.id, d.sku, d.slug, d.nombre, d.descripcion, d.precio, d.imagen, d.activo
FROM categorias c
JOIN (
    -- HAMBURGUESAS: LEAGUE OF LEGENDS (4)
    SELECT 'hamburguesas' categoria, 'RE-HAM-NOXUS' sku, 'noxus' slug, 'Noxus' nombre, 'Carne de vacuno, cheddar y bacon' descripcion, 11.90 precio, 'img/productos/noxus.webp' imagen, TRUE activo
    UNION ALL SELECT 'hamburguesas', 'RE-HAM-ZAUN', 'zaun', 'Zaun', 'Carne de vacuno, cheddar, bacon y jalapeños', 12.90, 'img/productos/zaun.webp', TRUE
    UNION ALL SELECT 'hamburguesas', 'RE-HAM-PILTOVER', 'piltover', 'Piltover', 'Carne de vacuno, cheddar, lechuga y tomate', 13.50, 'img/productos/piltover.webp', TRUE
    UNION ALL SELECT 'hamburguesas', 'RE-HAM-ALISTAR', 'hamburguesa-alistar', 'Hamburguesa Alistar', 'Doble carne de búfalo con queso gouda y cebolla crujiente', 16.90, 'img/productos/alistar.webp', TRUE

    -- HAMBURGUESAS: VALORANT (4)
    UNION ALL SELECT 'hamburguesas', 'RE-HAM-BIND', 'bind', 'Bind', 'Carne de vacuno, cheddar, cebolla crujiente, pepinillos y bacon caramelizado', 13.50, 'img/productos/bind.webp', TRUE
    UNION ALL SELECT 'hamburguesas', 'RE-HAM-HAVEN', 'haven', 'Haven', 'Pollo crujiente, lechuga, mayonesa picante y cheddar', 12.90, 'img/productos/haven.webp', TRUE
    UNION ALL SELECT 'hamburguesas', 'RE-HAM-ASCENT', 'ascent', 'Ascent', 'Carne de vacuno, queso provolone, mermelada de bacon y salsa especial', 13.90, 'img/productos/ascent.webp', TRUE
    UNION ALL SELECT 'hamburguesas', 'RE-HAM-SPLIT', 'split', 'Split', 'Doble carne de vacuno, doble cheddar, cebolla caramelizada y salsa BBQ', 14.50, 'img/productos/split.webp', TRUE

    -- HAMBURGUESAS: 2XKO (4)
    UNION ALL SELECT 'hamburguesas', 'RE-HAM-TAG-TEAM', 'tag-team', 'Tag Team', 'Doble carne picante, queso pepper jack y bacon ahumado', 14.90, 'img/productos/tag-team.webp', TRUE
    UNION ALL SELECT 'hamburguesas', 'RE-HAM-KO-PUNCH', 'ko-punch', 'KO Punch', 'Pollo empanado estilo frito, pepinillos y salsa buffalo', 12.50, 'img/productos/ko-punch.webp', TRUE
    UNION ALL SELECT 'hamburguesas', 'RE-HAM-ASSIST', 'assist', 'Assist', 'Hamburguesa de ternera con huevo frito, cheddar y salsa secreta', 13.20, 'img/productos/assist.webp', TRUE
    UNION ALL SELECT 'hamburguesas', 'RE-HAM-COUNTER', 'counter', 'Counter', 'Carne de vacuno, guacamole, jalapeños y queso fundido', 13.80, 'img/productos/counter.webp', TRUE

    -- HAMBURGUESAS: TFT (4)
    UNION ALL SELECT 'hamburguesas', 'RE-HAM-PENGU', 'pengu', 'Pengu', 'Hamburguesa estilo smash con doble cheddar y pepinillos', 11.50, 'img/productos/pengu.webp', TRUE
    UNION ALL SELECT 'hamburguesas', 'RE-HAM-CHIBI', 'chibi', 'Chibi', 'Hamburguesa individual con queso cheddar y bacon', 10.50, 'img/productos/chibi.webp', TRUE
    UNION ALL SELECT 'hamburguesas', 'RE-HAM-CAROUSEL', 'carousel', 'Carousel', 'Carne de vacuno, aros de cebolla, bacon y queso gouda', 13.90, 'img/productos/carousel.webp', TRUE
    UNION ALL SELECT 'hamburguesas', 'RE-HAM-HYPERROLL', 'hyperroll', 'Hyperroll', 'Triple carne smash, triple cheddar y bacon ahumado', 15.90, 'img/productos/hyperroll.webp', TRUE

    -- COMPLEMENTOS (4)
    UNION ALL SELECT 'complementos', 'RE-ACC-TEQUENOS', 'tequenos', 'Tequeños', 'Tequeños rellenos de queso fundido', 6.50, 'img/productos/tequenos.webp', TRUE
    UNION ALL SELECT 'complementos', 'RE-ACC-NACHOS', 'nachos', 'Nachos', 'Nachos con queso, guacamole y jalapeños', 7.50, 'img/productos/nachos.webp', TRUE
    UNION ALL SELECT 'complementos', 'RE-ACC-PATATAS', 'patatas-fritas', 'Patatas Fritas', 'Ración de patatas fritas crujientes', 3.50, 'img/productos/patatas.webp', TRUE
    UNION ALL SELECT 'complementos', 'RE-ACC-AROS-CEBOLLA', 'aros-cebolla', 'Aros de Cebolla', 'Aros de cebolla rebozados', 4.90, 'img/productos/aros-cebolla.webp', TRUE

    -- POSTRES (4)
    UNION ALL SELECT 'postres', 'RE-POS-CREPES', 'crepes', 'Crepes', 'Crepes rellenos de chocolate', 4.50, 'img/productos/crepes.webp', TRUE
    UNION ALL SELECT 'postres', 'RE-POS-HELADO-VAINILLA', 'helado-vainilla', 'Helado de Vainilla', 'Tina de helado sabor vainilla', 3.90, 'img/productos/helado-vainilla.webp', TRUE
    UNION ALL SELECT 'postres', 'RE-POS-COULANT', 'coulant', 'Coulant', 'Coulant de chocolate con corazón fundido', 4.90, 'img/productos/coulant.webp', TRUE
    UNION ALL SELECT 'postres', 'RE-POS-CHEESECAKE', 'cheesecake', 'Cheesecake', 'Tarta de queso estilo New York', 4.90, 'img/productos/cheesecake.webp', TRUE

    -- BEBIDAS NO ALCOHÓLICAS (8)
    UNION ALL SELECT 'bebidas', 'RE-BEB-AGUA', 'agua', 'Agua', 'Agua mineral 50cl', 1.50, 'img/productos/agua.webp', TRUE
    UNION ALL SELECT 'bebidas', 'RE-BEB-COCACOLA', 'coca-cola', 'Coca-Cola', 'Refresco Coca-Cola 33cl', 2.50, 'img/productos/cocacola.webp', TRUE
    UNION ALL SELECT 'bebidas', 'RE-BEB-NESTEA', 'nestea', 'Nestea', 'Té frío al limón 33cl', 2.50, 'img/productos/nestea.webp', TRUE
    UNION ALL SELECT 'bebidas', 'RE-BEB-FANTA-NARANJA', 'fanta-naranja', 'Fanta Naranja', 'Refresco de naranja 33cl', 2.50, 'img/productos/fanta-naranja.webp', TRUE
    UNION ALL SELECT 'bebidas', 'RE-BEB-FANTA-LIMON', 'fanta-limon', 'Fanta Limón', 'Refresco de limón 33cl', 2.50, 'img/productos/fanta-limon.webp', TRUE
    UNION ALL SELECT 'bebidas', 'RE-BEB-SPRITE', 'sprite', 'Sprite', 'Refresco de lima-limón 33cl', 2.50, 'img/productos/sprite.webp', TRUE
    UNION ALL SELECT 'bebidas', 'RE-BEB-AQUARIUS', 'aquarius', 'Aquarius', 'Bebida isotónica 33cl', 2.50, 'img/productos/aquarius.webp', TRUE
    UNION ALL SELECT 'bebidas', 'RE-BEB-MONSTER', 'monster', 'Monster Energy', 'Bebida energética 50cl', 3.50, 'img/productos/monster.webp', TRUE

    -- BEBIDAS CON ALCOHOL (5)
    UNION ALL SELECT 'bebidas-con-alcohol', 'RE-ALC-RED-BUFF', 'red-buff', 'Red Buff', 'Cóctel a base de vodka y bebidas rojas', 7.50, 'img/productos/red-buff.webp', TRUE
    UNION ALL SELECT 'bebidas-con-alcohol', 'RE-ALC-BLUE-BUFF', 'blue-buff', 'Blue Buff', 'Cóctel azul con ginebra y curacao', 7.50, 'img/productos/blue-buff.webp', TRUE
    UNION ALL SELECT 'bebidas-con-alcohol', 'RE-ALC-VOID', 'void', 'Void', 'Cóctel místico con ron y frutos negros', 8.00, 'img/productos/void.webp', TRUE
    UNION ALL SELECT 'bebidas-con-alcohol', 'RE-ALC-SPIKE', 'spike-drink', 'Spike', 'Chupete/Combinado potente de la casa', 6.50, 'img/productos/spike-drink.webp', TRUE
    UNION ALL SELECT 'bebidas-con-alcohol', 'RE-ALC-YASUO', 'yasuo', 'Yasuo', 'Combinado de sake con toque cítrico', 7.00, 'img/productos/yasuo.webp', TRUE

    -- PACKS (5)
    UNION ALL SELECT 'packs', 'RE-PAK-NOXUS', 'pack-noxus', 'Pack Noxus', 'Hamburguesa LoL + Patatas Fritas + Bebida', 15.90, 'img/productos/pack-noxus.webp', TRUE
    UNION ALL SELECT 'packs', 'RE-PAK-CLUTCH', 'pack-clutch', 'Pack Clutch', 'Hamburguesa Valorant + Patatas Fritas + Bebida', 16.50, 'img/productos/pack-clutch.webp', TRUE
    UNION ALL SELECT 'packs', 'RE-PAK-FLEX', 'pack-flex', 'Pack Flex', '5 Hamburguesas LoL + 5 Patatas Fritas + 5 Bebidas', 69.90, 'img/productos/pack-flex.webp', TRUE
    UNION ALL SELECT 'packs', 'RE-PAK-2XKO', 'pack-2xko', 'Pack 2XKO', 'Hamburguesa 2XKO + Patatas + Complemento + Bebida + Helado', 22.90, 'img/productos/pack-2xko.webp', TRUE
    UNION ALL SELECT 'packs', 'RE-PAK-CARDS', 'pack-cards', 'Pack Cards', 'Hamburguesa TFT + Patatas Fritas + Bebida', 14.90, 'img/productos/pack-cards.webp', TRUE

) d ON d.categoria = c.slug;

-- ============================================================
-- 14. INGREDIENTES BASE
-- ============================================================

INSERT INTO ingredientes (sku, nombre, precio_extra, activo) VALUES
('RE-ING-CARNE-VACUNO', 'Carne de vacuno', 3.50, TRUE),
('RE-ING-CARNE-BUFALO', 'Carne de búfalo', 4.00, TRUE),
('RE-ING-POLLO', 'Pollo crujiente', 3.00, TRUE),
('RE-ING-PAN', 'Pan de hamburguesa', 0.00, TRUE),
('RE-ING-CHEDDAR', 'Queso cheddar', 1.20, TRUE),
('RE-ING-GOUDA', 'Queso gouda', 1.20, TRUE),
('RE-ING-PEPPERJACK', 'Queso pepper jack', 1.30, TRUE),
('RE-ING-PROVOLONE', 'Queso provolone', 1.50, TRUE),
('RE-ING-BACON', 'Bacon', 1.50, TRUE),
('RE-ING-BACON-CARAMELIZADO', 'Bacon caramelizado', 1.80, TRUE),
('RE-ING-JALAPENOS', 'Jalapeños', 0.80, TRUE),
('RE-ING-CEBOLLA-CRUJIENTE', 'Cebolla crujiente', 0.90, TRUE),
('RE-ING-CEBOLLA-CARAMELIZADA', 'Cebolla caramelizada', 1.00, TRUE),
('RE-ING-PEPINILLOS', 'Pepinillos', 0.50, TRUE),
('RE-ING-LECHUGA', 'Lechuga', 0.30, TRUE),
('RE-ING-TOMATE', 'Tomate', 0.30, TRUE),
('RE-ING-HUEVO', 'Huevo frito', 1.20, TRUE),
('RE-ING-GUACAMOLE', 'Guacamole', 1.50, TRUE),
('RE-ING-AROS-CEBOLLA', 'Aros de cebolla', 1.20, TRUE),
('RE-ING-SALSA-HAMBURGUESA', 'Salsa de hamburguesa', 0.50, TRUE),
('RE-ING-SALSA-BBQ', 'Salsa BBQ', 0.50, TRUE),
('RE-ING-SALSA-BUFFALO', 'Salsa Buffalo', 0.60, TRUE);

-- ============================================================
-- 15. RECETAS DE HAMBURGUESAS (PRODUCTO_INGREDIENTE)
-- ============================================================

INSERT INTO producto_ingrediente (producto_id, ingrediente_id, cantidad)
SELECT p.id, i.id, 1.00
FROM (
    -- Noxus
    SELECT 'noxus' slug, 'Carne de vacuno' ingrediente UNION ALL SELECT 'noxus', 'Pan de hamburguesa' UNION ALL SELECT 'noxus', 'Queso cheddar' UNION ALL SELECT 'noxus', 'Bacon' UNION ALL SELECT 'noxus', 'Salsa de hamburguesa'
    -- Zaun
    UNION ALL SELECT 'zaun', 'Carne de vacuno' UNION ALL SELECT 'zaun', 'Pan de hamburguesa' UNION ALL SELECT 'zaun', 'Queso cheddar' UNION ALL SELECT 'zaun', 'Bacon' UNION ALL SELECT 'zaun', 'Jalapeños'
    -- Piltover
    UNION ALL SELECT 'piltover', 'Carne de vacuno' UNION ALL SELECT 'piltover', 'Pan de hamburguesa' UNION ALL SELECT 'piltover', 'Queso cheddar' UNION ALL SELECT 'piltover', 'Lechuga' UNION ALL SELECT 'piltover', 'Tomate'
    -- Hamburguesa Alistar
    UNION ALL SELECT 'hamburguesa-alistar', 'Carne de búfalo' UNION ALL SELECT 'hamburguesa-alistar', 'Pan de hamburguesa' UNION ALL SELECT 'hamburguesa-alistar', 'Queso gouda' UNION ALL SELECT 'hamburguesa-alistar', 'Cebolla crujiente'
    -- Bind
    UNION ALL SELECT 'bind', 'Carne de vacuno' UNION ALL SELECT 'bind', 'Pan de hamburguesa' UNION ALL SELECT 'bind', 'Queso cheddar' UNION ALL SELECT 'bind', 'Cebolla crujiente' UNION ALL SELECT 'bind', 'Pepinillos' UNION ALL SELECT 'bind', 'Bacon caramelizado'
    -- Haven
    UNION ALL SELECT 'haven', 'Pollo crujiente' UNION ALL SELECT 'haven', 'Pan de hamburguesa' UNION ALL SELECT 'haven', 'Queso cheddar' UNION ALL SELECT 'haven', 'Lechuga' UNION ALL SELECT 'haven', 'Salsa Buffalo'
    -- Ascent
    UNION ALL SELECT 'ascent', 'Carne de vacuno' UNION ALL SELECT 'ascent', 'Pan de hamburguesa' UNION ALL SELECT 'ascent', 'Queso provolone' UNION ALL SELECT 'ascent', 'Bacon caramelizado'
    -- Split
    UNION ALL SELECT 'split', 'Carne de vacuno' UNION ALL SELECT 'split', 'Pan de hamburguesa' UNION ALL SELECT 'split', 'Queso cheddar' UNION ALL SELECT 'split', 'Cebolla caramelizada' UNION ALL SELECT 'split', 'Salsa BBQ'
    -- Tag Team
    UNION ALL SELECT 'tag-team', 'Carne de vacuno' UNION ALL SELECT 'tag-team', 'Pan de hamburguesa' UNION ALL SELECT 'tag-team', 'Queso pepper jack' UNION ALL SELECT 'tag-team', 'Bacon' UNION ALL SELECT 'tag-team', 'Jalapeños'
    -- KO Punch
    UNION ALL SELECT 'ko-punch', 'Pollo crujiente' UNION ALL SELECT 'ko-punch', 'Pan de hamburguesa' UNION ALL SELECT 'ko-punch', 'Pepinillos' UNION ALL SELECT 'ko-punch', 'Salsa Buffalo'
    -- Assist
    UNION ALL SELECT 'assist', 'Carne de vacuno' UNION ALL SELECT 'assist', 'Pan de hamburguesa' UNION ALL SELECT 'assist', 'Queso cheddar' UNION ALL SELECT 'assist', 'Huevo frito' UNION ALL SELECT 'assist', 'Salsa de hamburguesa'
    -- Counter
    UNION ALL SELECT 'counter', 'Carne de vacuno' UNION ALL SELECT 'counter', 'Pan de hamburguesa' UNION ALL SELECT 'counter', 'Guacamole' UNION ALL SELECT 'counter', 'Jalapeños' UNION ALL SELECT 'counter', 'Queso pepper jack'
    -- Pengu
    UNION ALL SELECT 'pengu', 'Carne de vacuno' UNION ALL SELECT 'pengu', 'Pan de hamburguesa' UNION ALL SELECT 'pengu', 'Queso cheddar' UNION ALL SELECT 'pengu', 'Pepinillos'
    -- Chibi
    UNION ALL SELECT 'chibi', 'Carne de vacuno' UNION ALL SELECT 'chibi', 'Pan de hamburguesa' UNION ALL SELECT 'chibi', 'Queso cheddar' UNION ALL SELECT 'chibi', 'Bacon'
    -- Carousel
    UNION ALL SELECT 'carousel', 'Carne de vacuno' UNION ALL SELECT 'carousel', 'Pan de hamburguesa' UNION ALL SELECT 'carousel', 'Queso gouda' UNION ALL SELECT 'carousel', 'Aros de cebolla' UNION ALL SELECT 'carousel', 'Bacon'
    -- Hyperroll
    UNION ALL SELECT 'hyperroll', 'Carne de vacuno' UNION ALL SELECT 'hyperroll', 'Pan de hamburguesa' UNION ALL SELECT 'hyperroll', 'Queso cheddar' UNION ALL SELECT 'hyperroll', 'Bacon' UNION ALL SELECT 'hyperroll', 'Salsa BBQ'
) receta
JOIN productos p ON p.slug = receta.slug
JOIN ingredientes i ON i.nombre = receta.ingrediente;

-- ============================================================
-- 16. COMPONENTES DE LOS PACKS (PRODUCTO_COMBO)
-- ============================================================

INSERT INTO producto_combo (combo_id, producto_id, cantidad)
SELECT combo.id, componente.id, d.cantidad
FROM (
    -- Pack Noxus (Hamburguesa LoL + Patatas + Bebida)
    SELECT 'pack-noxus' combo, 'noxus' componente, 1 cantidad
    UNION ALL SELECT 'pack-noxus', 'patatas-fritas', 1
    UNION ALL SELECT 'pack-noxus', 'coca-cola', 1

    -- Pack Clutch (Hamburguesa Valorant + Patatas + Bebida)
    UNION ALL SELECT 'pack-clutch', 'bind', 1
    UNION ALL SELECT 'pack-clutch', 'patatas-fritas', 1
    UNION ALL SELECT 'pack-clutch', 'coca-cola', 1

    -- Pack Flex (5 Hamburguesas LoL + 5 Patatas + 5 Bebidas)
    UNION ALL SELECT 'pack-flex', 'noxus', 5
    UNION ALL SELECT 'pack-flex', 'patatas-fritas', 5
    UNION ALL SELECT 'pack-flex', 'coca-cola', 5

    -- Pack 2XKO (Hamburguesa 2XKO + Patatas + Complemento + Bebida + Helado)
    UNION ALL SELECT 'pack-2xko', 'tag-team', 1
    UNION ALL SELECT 'pack-2xko', 'patatas-fritas', 1
    UNION ALL SELECT 'pack-2xko', 'tequenos', 1
    UNION ALL SELECT 'pack-2xko', 'coca-cola', 1
    UNION ALL SELECT 'pack-2xko', 'helado-vainilla', 1

    -- Pack Cards (Hamburguesa TFT + Patatas + Bebida)
    UNION ALL SELECT 'pack-cards', 'pengu', 1
    UNION ALL SELECT 'pack-cards', 'patatas-fritas', 1
    UNION ALL SELECT 'pack-cards', 'coca-cola', 1

) d
JOIN productos combo ON combo.slug = d.combo
JOIN productos componente ON componente.slug = d.componente;

-- ============================================================
-- 17. OFERTAS
-- ============================================================

INSERT INTO ofertas (nombre, descripcion, tipo, valor, codigo, fecha_inicio, fecha_fin, activa) VALUES
('Oferta de lanzamiento', '10% de descuento en hamburguesas de LoL', 'descuento', 10.00, NULL, '2026-01-01 00:00:00', '2027-12-31 23:59:59', TRUE),
('Bebidas 2x1', 'Dos bebidas por el precio de una', '2x1', NULL, NULL, '2026-01-01 00:00:00', '2027-12-31 23:59:59', TRUE),
('Codigo RIOT10', 'Código promocional del 10%', 'codigo', 10.00, 'RIOT10', '2026-01-01 00:00:00', '2027-12-31 23:59:59', TRUE),
('Codigo GAMER20', 'Código promocional del 20%', 'codigo', 20.00, 'GAMER20', '2026-01-01 00:00:00', '2027-12-31 23:59:59', TRUE);

-- ============================================================
-- 18. PRODUCTOS DE CADA OFERTA
-- ============================================================

INSERT INTO oferta_producto (oferta_id, producto_id)
SELECT o.id, p.id
FROM ofertas o
JOIN productos p ON 
(o.nombre = 'Oferta de lanzamiento' AND p.slug IN ('noxus','zaun','piltover')) OR
(o.nombre = 'Bebidas 2x1' AND p.slug IN ('coca-cola','agua')) OR
(o.nombre = 'Codigo RIOT10' AND p.slug IN ('bind','tag-team')) OR
(o.nombre = 'Codigo GAMER20' AND p.slug IN ('hamburguesa-alistar','hyperroll'));

-- ============================================================
-- 19. USUARIOS DE PRUEBA
-- ============================================================

INSERT INTO usuarios (nombre, email, password, telefono, direccion, fecha_nacimiento, rol) VALUES
('admin', 'admin@rioteats.local', '$2y$12$1BSXuAxdTT7o1Wefne1DqOaEEqQ5K/dq2GNTg5dNxkx57svyep/Y6', '600111222', 'Calle Admin 10, Barcelona', '1995-05-10', 'admin'),
('user_1', 'user_1@rioteats.local', '$2y$12$y21TMfgZenHdwmHHrgHERerfYcPPPPeCYifJ48f3Nv7IDYXiWWhFu', '600333444', 'Avenida Gamer 25, Barcelona', '2001-08-15', 'usuario');

-- ============================================================
-- 20. PEDIDOS DE PRUEBA
-- ============================================================

INSERT INTO pedidos (usuario_id, estado, fecha_pedido, subtotal, descuento, total, metodo_pago, estado_pago, oferta_id, codigo_oferta, referencia_prueba) VALUES
((SELECT id FROM usuarios WHERE nombre='admin'), 'entregado', '2026-10-20 14:30:00', 17.90, 1.79, 16.11, 'tarjeta', 'pagado', (SELECT id FROM ofertas WHERE nombre='Oferta de lanzamiento'), NULL, 'ADM-001'),
((SELECT id FROM usuarios WHERE nombre='admin'), 'cancelado', '2026-10-21 20:15:00', 18.40, 0.00, 18.40, 'tarjeta', 'fallido', NULL, NULL, 'ADM-002'),
((SELECT id FROM usuarios WHERE nombre='admin'), 'preparando', '2026-10-29 13:00:00', 24.30, 0.00, 24.30, 'simulado', 'pendiente', NULL, NULL, 'ADM-003'),
((SELECT id FROM usuarios WHERE nombre='admin'), 'entregado', '2026-10-25 21:00:00', 69.90, 0.00, 69.90, 'tarjeta', 'pagado', NULL, NULL, 'ADM-004'),
((SELECT id FROM usuarios WHERE nombre='user_1'), 'entregado', '2026-10-18 15:30:00', 18.50, 0.00, 18.50, 'efectivo', 'pagado', NULL, NULL, 'USR-001'),
((SELECT id FROM usuarios WHERE nombre='user_1'), 'confirmado', '2026-10-28 19:45:00', 32.80, 6.56, 26.24, 'tarjeta', 'pagado', (SELECT id FROM ofertas WHERE codigo='GAMER20'), 'GAMER20', 'USR-002'),
((SELECT id FROM usuarios WHERE nombre='user_1'), 'enviado', '2026-10-29 20:00:00', 16.50, 0.00, 16.50, 'tarjeta', 'pagado', NULL, NULL, 'USR-003'),
((SELECT id FROM usuarios WHERE nombre='user_1'), 'pendiente', '2026-10-30 18:00:00', 7.40, 0.00, 7.40, 'simulado', 'pendiente', NULL, NULL, 'USR-004'),
((SELECT id FROM usuarios WHERE nombre='user_1'), 'entregado', '2026-10-26 12:30:00', 5.00, 2.50, 2.50, 'tarjeta', 'pagado', (SELECT id FROM ofertas WHERE nombre='Bebidas 2x1'), NULL, 'USR-005');

-- ============================================================
-- 21. DETALLES DE PEDIDOS
-- ============================================================

INSERT INTO pedido_detalle (pedido_id, producto_id, nombre_producto, precio_unitario, cantidad, subtotal, referencia_prueba) VALUES
((SELECT id FROM pedidos WHERE referencia_prueba='ADM-001'), (SELECT id FROM productos WHERE slug='noxus'), 'Noxus', 11.90, 1, 11.90, 'ADM-001-01'),
((SELECT id FROM pedidos WHERE referencia_prueba='ADM-001'), (SELECT id FROM productos WHERE slug='patatas-fritas'), 'Patatas Fritas', 3.50, 1, 3.50, 'ADM-001-02'),
((SELECT id FROM pedidos WHERE referencia_prueba='ADM-001'), (SELECT id FROM productos WHERE slug='coca-cola'), 'Coca-Cola', 2.50, 1, 2.50, 'ADM-001-03'),

((SELECT id FROM pedidos WHERE referencia_prueba='ADM-002'), (SELECT id FROM productos WHERE slug='zaun'), 'Zaun', 12.90, 1, 12.90, 'ADM-002-01'),
((SELECT id FROM pedidos WHERE referencia_prueba='ADM-002'), (SELECT id FROM productos WHERE slug='tequenos'), 'Tequeños', 6.50, 1, 6.50, 'ADM-002-02'),

((SELECT id FROM pedidos WHERE referencia_prueba='USR-001'), (SELECT id FROM productos WHERE slug='bind'), 'Bind', 13.50, 1, 13.50, 'USR-001-01'),
((SELECT id FROM pedidos WHERE referencia_prueba='USR-001'), (SELECT id FROM productos WHERE slug='patatas-fritas'), 'Patatas Fritas', 3.50, 1, 3.50, 'USR-001-02'),
((SELECT id FROM pedidos WHERE referencia_prueba='USR-001'), (SELECT id FROM productos WHERE slug='agua'), 'Agua', 1.50, 1, 1.50, 'USR-001-03'),

((SELECT id FROM pedidos WHERE referencia_prueba='USR-005'), (SELECT id FROM productos WHERE slug='coca-cola'), 'Coca-Cola', 2.50, 2, 5.00, 'USR-005-01');

-- ============================================================
-- 22. HISTORIAL DE INGREDIENTES EN PEDIDOS
-- ============================================================

INSERT INTO pedido_detalle_ingrediente (pedido_detalle_id, ingrediente_id, nombre_ingrediente, precio_extra, accion) VALUES
((SELECT id FROM pedido_detalle WHERE referencia_prueba='ADM-001-01'), (SELECT id FROM ingredientes WHERE nombre='Jalapeños'), 'Jalapeños', 0.80, 'añadido'),
((SELECT id FROM pedido_detalle WHERE referencia_prueba='ADM-002-01'), (SELECT id FROM ingredientes WHERE nombre='Jalapeños'), 'Jalapeños', 0.00, 'quitado'),
((SELECT id FROM pedido_detalle WHERE referencia_prueba='USR-001-01'), (SELECT id FROM ingredientes WHERE nombre='Pepinillos'), 'Pepinillos', 0.00, 'quitado');

-- Re-activar verificación de llaves foráneas
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- ASIGNACIÓN DE IMÁGENES A TODOS LOS PRODUCTOS DE LA BBDD
-- ============================================================

-- 1. HAMBURGUESAS: LEAGUE OF LEGENDS
UPDATE productos SET imagen = 'img/productos/noxus.webp' WHERE slug = 'noxus';
UPDATE productos SET imagen = 'img/productos/zaun.webp' WHERE slug = 'zaun';
UPDATE productos SET imagen = 'img/productos/piltover.webp' WHERE slug = 'piltover';
UPDATE productos SET imagen = 'img/productos/alistar.webp' WHERE slug = 'hamburguesa-alistar';

-- 2. HAMBURGUESAS: VALORANT
UPDATE productos SET imagen = 'img/productos/bind.webp' WHERE slug = 'bind';
UPDATE productos SET imagen = 'img/productos/haven.webp' WHERE slug = 'haven';
UPDATE productos SET imagen = 'img/productos/ascent.webp' WHERE slug = 'ascent';
UPDATE productos SET imagen = 'img/productos/split.webp' WHERE slug = 'split';

-- 3. HAMBURGUESAS: 2XKO
UPDATE productos SET imagen = 'img/productos/tag-team.webp' WHERE slug = 'tag-team';
UPDATE productos SET imagen = 'img/productos/ko-punch.webp' WHERE slug = 'ko-punch';
UPDATE productos SET imagen = 'img/productos/assist.webp' WHERE slug = 'assist';
UPDATE productos SET imagen = 'img/productos/counter.webp' WHERE slug = 'counter';

-- 4. HAMBURGUESAS: TFT
UPDATE productos SET imagen = 'img/productos/pengu.webp' WHERE slug = 'pengu';
UPDATE productos SET imagen = 'img/productos/chibi.webp' WHERE slug = 'chibi';
UPDATE productos SET imagen = 'img/productos/carousel.webp' WHERE slug = 'carousel';
UPDATE productos SET imagen = 'img/productos/hyperroll.webp' WHERE slug = 'hyperroll';

-- 5. COMPLEMENTOS
UPDATE productos SET imagen = 'img/productos/tequenos.webp' WHERE slug = 'tequenos';
UPDATE productos SET imagen = 'img/productos/nachos.webp' WHERE slug = 'nachos';
UPDATE productos SET imagen = 'img/productos/patatas.webp' WHERE slug = 'patatas-fritas';
UPDATE productos SET imagen = 'img/productos/aros-cebolla.webp' WHERE slug = 'aros-cebolla';

-- 6. POSTRES
UPDATE productos SET imagen = 'img/productos/crepes.webp' WHERE slug = 'crepes';
UPDATE productos SET imagen = 'img/productos/helado-vainilla.webp' WHERE slug = 'helado-vainilla';
UPDATE productos SET imagen = 'img/productos/coulant.webp' WHERE slug = 'coulant';
UPDATE productos SET imagen = 'img/productos/cheesecake.webp' WHERE slug = 'cheesecake';

-- 7. BEBIDAS NO ALCOHÓLICAS
UPDATE productos SET imagen = 'img/productos/agua.webp' WHERE slug = 'agua';
UPDATE productos SET imagen = 'img/productos/cocacola.webp' WHERE slug = 'coca-cola';
UPDATE productos SET imagen = 'img/productos/nestea.webp' WHERE slug = 'nestea';
UPDATE productos SET imagen = 'img/productos/fanta-naranja.webp' WHERE slug = 'fanta-naranja';
UPDATE productos SET imagen = 'img/productos/fanta-limon.webp' WHERE slug = 'fanta-limon';
UPDATE productos SET imagen = 'img/productos/sprite.webp' WHERE slug = 'sprite';
UPDATE productos SET imagen = 'img/productos/aquarius.webp' WHERE slug = 'aquarius';
UPDATE productos SET imagen = 'img/productos/monster.webp' WHERE slug = 'monster';

-- 8. BEBIDAS CON ALCOHOL
UPDATE productos SET imagen = 'img/productos/red-buff.webp' WHERE slug = 'red-buff';
UPDATE productos SET imagen = 'img/productos/blue-buff.webp' WHERE slug = 'blue-buff';
UPDATE productos SET imagen = 'img/productos/void.webp' WHERE slug = 'void';
UPDATE productos SET imagen = 'img/productos/spike-drink.webp' WHERE slug = 'spike-drink';
UPDATE productos SET imagen = 'img/productos/yasuo.webp' WHERE slug = 'yasuo';

-- 9. PACKS / COMBOS
UPDATE productos SET imagen = 'img/productos/pack-noxus.webp' WHERE slug = 'pack-noxus';
UPDATE productos SET imagen = 'img/productos/pack-clutch.webp' WHERE slug = 'pack-clutch';
UPDATE productos SET imagen = 'img/productos/pack-flex.webp' WHERE slug = 'pack-flex';
UPDATE productos SET imagen = 'img/productos/pack-2xko.webp' WHERE slug = 'pack-2xko';
UPDATE productos SET imagen = 'img/productos/pack-cards.webp' WHERE slug = 'pack-cards';

-- Comprobación: Muestra el nombre y la imagen de todos los productos
SELECT id, nombre, slug, imagen FROM productos ORDER BY id ASC;

-- ============================================================
-- 23. CONSULTAS DE COMPROBACIÓN (SELECT * DE CADA TABLA)
-- ============================================================

SELECT * FROM usuarios;
SELECT * FROM categorias;
SELECT * FROM productos ORDER BY id ASC;
SELECT * FROM ingredientes;
SELECT * FROM producto_ingrediente;
SELECT * FROM producto_combo;
SELECT * FROM ofertas;
SELECT * FROM oferta_producto;
SELECT * FROM pedidos;
SELECT * FROM pedido_detalle;
SELECT * FROM pedido_detalle_ingrediente;