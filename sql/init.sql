
-- ============================================================
-- RIOT EATS
-- BASE DE DATOS COMPLETA + DATOS DE PRUEBA
-- MYSQL 8.4
-- ============================================================
-- USUARIOS:
-- admin  / Admin123!
-- user_1 / User123!
--
-- IMPORTANTE:
-- 1. ELIMINA TODAS LAS TABLAS DE riot_eats.
-- 2. RECREA TODA LA ESTRUCTURA.
-- 3. INSERTA TODOS LOS DATOS.
-- 4. NO DEBE EJECUTARSE SOBRE UNA BBDD QUE QUIERAS CONSERVAR.
-- ============================================================

CREATE DATABASE IF NOT EXISTS riot_eats
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE riot_eats;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 0. ELIMINAR TODAS LAS TABLAS EXISTENTES
-- ============================================================

SET SESSION group_concat_max_len = 1000000;

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
-- 5. RELACION PRODUCTO - INGREDIENTE
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
-- 6. COMPONENTES DE LOS COMBOS
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

    tipo VARCHAR(20) NOT NULL
    COMMENT 'descuento, 2x1, combo, codigo',

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

    FOREIGN KEY (usuario_id)
    REFERENCES usuarios(id),

    FOREIGN KEY (oferta_id)
    REFERENCES ofertas(id),

    CHECK (
        estado IN (
            'pendiente',
            'confirmado',
            'preparando',
            'enviado',
            'entregado',
            'cancelado'
        )
    ),

    CHECK (
        estado_pago IN (
            'pendiente',
            'pagado',
            'fallido'
        )
    ),

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

    FOREIGN KEY (pedido_id)
    REFERENCES pedidos(id),

    FOREIGN KEY (producto_id)
    REFERENCES productos(id)
    ON DELETE SET NULL,

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

    FOREIGN KEY (pedido_detalle_id)
    REFERENCES pedido_detalle(id),

    FOREIGN KEY (ingrediente_id)
    REFERENCES ingredientes(id)
    ON DELETE SET NULL,

    CHECK (precio_extra >= 0),

    CHECK (
        accion IN (
            'incluido',
            'quitado',
            'añadido'
        )
    )
) ENGINE=InnoDB;

-- ============================================================
-- 12. CATEGORIAS
-- ============================================================

INSERT INTO categorias
(nombre,slug,descripcion,activa)
VALUES
('Hamburguesas','hamburguesas',
'Hamburguesas tematicas de Riot Eats',TRUE),

('Combos','combos',
'Combos individuales y grupales',TRUE),

('Acompanamientos','acompanamientos',
'Patatas y acompanamientos',TRUE),

('Bebidas','bebidas',
'Bebidas disponibles',TRUE),

('Postres','postres',
'Postres disponibles',TRUE);

-- ============================================================
-- 13. PRODUCTOS
-- ============================================================

INSERT INTO productos
(categoria_id,sku,slug,nombre,descripcion,precio,imagen,activo)

SELECT
c.id,d.sku,d.slug,d.nombre,d.descripcion,d.precio,d.imagen,d.activo

FROM categorias c

JOIN (
    SELECT 'hamburguesas' categoria,
    'RE-HAM-NOXUS' sku,'noxus' slug,'Noxus' nombre,
    'Vacuno, cheddar y bacon' descripcion,
    11.90 precio,'img/productos/noxus.webp' imagen,TRUE activo

    UNION ALL SELECT 'hamburguesas',
    'RE-HAM-ZAUN','zaun','Zaun',
    'Vacuno, cheddar, bacon y jalapenos',
    12.90,'img/productos/zaun.webp',TRUE

    UNION ALL SELECT 'hamburguesas',
    'RE-HAM-PILTOVER','piltover','Piltover',
    'Vacuno, cheddar, lechuga y tomate',
    13.50,'img/productos/piltover.webp',TRUE

    UNION ALL SELECT 'hamburguesas',
    'RE-HAM-DEMACIA','demacia','Demacia',
    'Hamburguesa clasica de vacuno y cheddar',
    12.50,'img/productos/demacia.webp',TRUE

    UNION ALL SELECT 'hamburguesas',
    'RE-HAM-JONIA','jonia','Jonia',
    'Vacuno, cebolla y salsa especial',
    12.90,'img/productos/jonia.webp',TRUE

    UNION ALL SELECT 'hamburguesas',
    'RE-HAM-SHURIMA','shurima','Shurima',
    'Vacuno, cheddar, bacon y jalapenos',
    13.90,'img/productos/shurima.webp',TRUE

    UNION ALL SELECT 'hamburguesas',
    'RE-HAM-SPIKE','spike','Spike',
    'Hamburguesa especial de Riot Eats',
    11.50,'img/productos/spike.webp',FALSE

    UNION ALL SELECT 'hamburguesas',
    'RE-HAM-ASCENT','ascent','Ascent',
    'Vacuno, cheddar y bacon',
    12.50,'img/productos/ascent.webp',TRUE

    UNION ALL SELECT 'hamburguesas',
    'RE-HAM-BIND','bind','Bind',
    'Vacuno, cheddar, cebolla crujiente, pepinillos y bacon caramelizado',
    13.50,'img/productos/bind.webp',TRUE

    UNION ALL SELECT 'hamburguesas',
    'RE-HAM-SPLIT','split','Split',
    'Vacuno, lechuga, tomate y cebolla',
    12.90,'img/productos/split.webp',TRUE

    UNION ALL SELECT 'hamburguesas',
    'RE-HAM-TEMPLO-JONIA','templo-jonia','Templo Jonia',
    'Hamburguesa especial inspirada en Jonia',
    14.50,'img/productos/templo-jonia.webp',TRUE

    UNION ALL SELECT 'hamburguesas',
    'RE-HAM-ALISTAR','alistar','Alistar',
    'Hamburguesa de carne de bufalo con abundante carne',
    16.90,'img/productos/alistar.webp',TRUE

    UNION ALL SELECT 'hamburguesas',
    'RE-HAM-TFT','tft','TFT',
    'Hamburguesa especial inspirada en Teamfight Tactics',
    14.90,'img/productos/tft.webp',TRUE

    UNION ALL SELECT 'combos',
    'RE-COM-SOLO-QUEUE','solo-queue','Solo Queue',
    'Hamburguesa, patatas y bebida',
    17.90,'img/productos/solo-queue.webp',TRUE

    UNION ALL SELECT 'combos',
    'RE-COM-FLEX-QUEUE','flex-queue','Flex Queue',
    'Cinco hamburguesas, cinco patatas y cinco bebidas',
    75.50,'img/productos/flex-queue.webp',TRUE

    UNION ALL SELECT 'combos',
    'RE-COM-2XKO','2xko','2XKO',
    'Hamburguesa, patatas, nuggets, bebida y postre',
    24.90,'img/productos/2xko.webp',TRUE

    UNION ALL SELECT 'acompanamientos',
    'RE-ACC-PATATAS','patatas-fritas','Patatas fritas',
    'Racion de patatas fritas',
    3.50,'img/productos/patatas.webp',TRUE

    UNION ALL SELECT 'acompanamientos',
    'RE-ACC-NUGGETS','nuggets','Nuggets',
    'Nuggets de pollo',
    5.50,'img/productos/nuggets.webp',TRUE

    UNION ALL SELECT 'bebidas',
    'RE-BEB-COCACOLA','coca-cola','Coca-Cola',
    'Refresco Coca-Cola',
    2.50,'img/productos/cocacola.webp',TRUE

    UNION ALL SELECT 'bebidas',
    'RE-BEB-AGUA','agua','Agua',
    'Agua embotellada',
    1.50,'img/productos/agua.webp',TRUE

    UNION ALL SELECT 'postres',
    'RE-POS-HELADO','helado','Helado',
    'Postre helado',
    3.90,'img/productos/helado.webp',TRUE

) d ON d.categoria=c.slug;

-- ============================================================
-- 14. INGREDIENTES
-- ============================================================

INSERT INTO ingredientes
(sku,nombre,precio_extra,activo)
VALUES

('RE-ING-CARNE-VACUNO','Carne de vacuno',3.50,TRUE),
('RE-ING-CARNE-BUFALO','Carne de bufalo',4.00,TRUE),
('RE-ING-PAN','Pan de hamburguesa',0.00,TRUE),
('RE-ING-CHEDDAR','Queso cheddar',1.20,TRUE),
('RE-ING-BACON','Bacon',1.50,TRUE),
('RE-ING-JALAPENOS','Jalapenos',0.80,TRUE),
('RE-ING-CEBOLLA-CRUJIENTE','Cebolla crujiente',0.90,TRUE),
('RE-ING-PEPINILLOS','Pepinillos',0.50,TRUE),
('RE-ING-BACON-CARAMELIZADO','Bacon caramelizado',1.80,TRUE),
('RE-ING-LECHUGA','Lechuga',0.30,TRUE),
('RE-ING-TOMATE','Tomate',0.30,TRUE),
('RE-ING-CEBOLLA','Cebolla',0.30,TRUE),
('RE-ING-SALSA','Salsa de hamburguesa',0.50,TRUE),
('RE-ING-PATATAS','Patatas fritas',0.00,TRUE),
('RE-ING-NUGGETS','Nuggets de pollo',0.00,TRUE),
('RE-ING-COCACOLA','Coca-Cola',0.00,TRUE),
('RE-ING-AGUA','Agua',0.00,TRUE),
('RE-ING-HELADO','Helado',0.00,TRUE),

-- Ingrediente inactivo para pruebas
('RE-ING-TRUFA','Salsa de trufa',1.50,FALSE);

-- ============================================================
-- 15. RECETAS DE HAMBURGUESAS
-- ============================================================

INSERT INTO producto_ingrediente
(producto_id,ingrediente_id,cantidad)

SELECT p.id,i.id,1.00

FROM (
    SELECT 'noxus' slug,'Carne de vacuno' ingrediente
    UNION ALL SELECT 'noxus','Pan de hamburguesa'
    UNION ALL SELECT 'noxus','Queso cheddar'
    UNION ALL SELECT 'noxus','Bacon'
    UNION ALL SELECT 'noxus','Salsa de hamburguesa'

    UNION ALL SELECT 'zaun','Carne de vacuno'
    UNION ALL SELECT 'zaun','Pan de hamburguesa'
    UNION ALL SELECT 'zaun','Queso cheddar'
    UNION ALL SELECT 'zaun','Bacon'
    UNION ALL SELECT 'zaun','Jalapenos'
    UNION ALL SELECT 'zaun','Salsa de hamburguesa'

    UNION ALL SELECT 'piltover','Carne de vacuno'
    UNION ALL SELECT 'piltover','Pan de hamburguesa'
    UNION ALL SELECT 'piltover','Queso cheddar'
    UNION ALL SELECT 'piltover','Lechuga'
    UNION ALL SELECT 'piltover','Tomate'
    UNION ALL SELECT 'piltover','Salsa de hamburguesa'

    UNION ALL SELECT 'demacia','Carne de vacuno'
    UNION ALL SELECT 'demacia','Pan de hamburguesa'
    UNION ALL SELECT 'demacia','Queso cheddar'
    UNION ALL SELECT 'demacia','Lechuga'
    UNION ALL SELECT 'demacia','Tomate'

    UNION ALL SELECT 'jonia','Carne de vacuno'
    UNION ALL SELECT 'jonia','Pan de hamburguesa'
    UNION ALL SELECT 'jonia','Cebolla'
    UNION ALL SELECT 'jonia','Salsa de hamburguesa'

    UNION ALL SELECT 'shurima','Carne de vacuno'
    UNION ALL SELECT 'shurima','Pan de hamburguesa'
    UNION ALL SELECT 'shurima','Queso cheddar'
    UNION ALL SELECT 'shurima','Bacon'
    UNION ALL SELECT 'shurima','Jalapenos'

    UNION ALL SELECT 'spike','Carne de vacuno'
    UNION ALL SELECT 'spike','Pan de hamburguesa'
    UNION ALL SELECT 'spike','Queso cheddar'
    UNION ALL SELECT 'spike','Salsa de hamburguesa'

    UNION ALL SELECT 'ascent','Carne de vacuno'
    UNION ALL SELECT 'ascent','Pan de hamburguesa'
    UNION ALL SELECT 'ascent','Queso cheddar'
    UNION ALL SELECT 'ascent','Bacon'

    UNION ALL SELECT 'bind','Carne de vacuno'
    UNION ALL SELECT 'bind','Pan de hamburguesa'
    UNION ALL SELECT 'bind','Queso cheddar'
    UNION ALL SELECT 'bind','Cebolla crujiente'
    UNION ALL SELECT 'bind','Pepinillos'
    UNION ALL SELECT 'bind','Bacon caramelizado'

    UNION ALL SELECT 'split','Carne de vacuno'
    UNION ALL SELECT 'split','Pan de hamburguesa'
    UNION ALL SELECT 'split','Lechuga'
    UNION ALL SELECT 'split','Tomate'
    UNION ALL SELECT 'split','Cebolla'

    UNION ALL SELECT 'templo-jonia','Carne de vacuno'
    UNION ALL SELECT 'templo-jonia','Pan de hamburguesa'
    UNION ALL SELECT 'templo-jonia','Queso cheddar'
    UNION ALL SELECT 'templo-jonia','Bacon caramelizado'
    UNION ALL SELECT 'templo-jonia','Salsa de hamburguesa'

    UNION ALL SELECT 'alistar','Carne de bufalo'
    UNION ALL SELECT 'alistar','Pan de hamburguesa'
    UNION ALL SELECT 'alistar','Queso cheddar'
    UNION ALL SELECT 'alistar','Bacon'

    UNION ALL SELECT 'tft','Carne de vacuno'
    UNION ALL SELECT 'tft','Pan de hamburguesa'
    UNION ALL SELECT 'tft','Queso cheddar'
    UNION ALL SELECT 'tft','Bacon'
    UNION ALL SELECT 'tft','Cebolla crujiente'

) receta

JOIN productos p ON p.slug=receta.slug
JOIN ingredientes i ON i.nombre=receta.ingrediente;

-- ============================================================
-- 16. RECETAS DE ACOMPANAMIENTOS, BEBIDAS Y POSTRES
-- ============================================================

INSERT INTO producto_ingrediente
(producto_id,ingrediente_id,cantidad)

SELECT p.id,i.id,1.00
FROM (
    SELECT 'patatas-fritas' slug,'Patatas fritas' ingrediente
    UNION ALL SELECT 'nuggets','Nuggets de pollo'
    UNION ALL SELECT 'coca-cola','Coca-Cola'
    UNION ALL SELECT 'agua','Agua'
    UNION ALL SELECT 'helado','Helado'
) receta
JOIN productos p ON p.slug=receta.slug
JOIN ingredientes i ON i.nombre=receta.ingrediente;

-- ============================================================
-- 17. COMPONENTES DE LOS COMBOS
-- ============================================================

-- SOLO QUEUE: 1 hamburguesa + patatas + bebida

INSERT INTO producto_combo
(combo_id,producto_id,cantidad)

SELECT combo.id,componente.id,d.cantidad

FROM (
    SELECT 'solo-queue' combo,'noxus' componente,1 cantidad
    UNION ALL SELECT 'solo-queue','patatas-fritas',1
    UNION ALL SELECT 'solo-queue','coca-cola',1

    -- FLEX QUEUE: cinco unidades de cada componente
    UNION ALL SELECT 'flex-queue','noxus',5
    UNION ALL SELECT 'flex-queue','patatas-fritas',5
    UNION ALL SELECT 'flex-queue','agua',5

    -- 2XKO
    UNION ALL SELECT '2xko','noxus',1
    UNION ALL SELECT '2xko','patatas-fritas',1
    UNION ALL SELECT '2xko','nuggets',1
    UNION ALL SELECT '2xko','coca-cola',1
    UNION ALL SELECT '2xko','helado',1

) d

JOIN productos combo ON combo.slug=d.combo
JOIN productos componente ON componente.slug=d.componente;

-- ============================================================
-- 18. OFERTAS
-- ============================================================

INSERT INTO ofertas
(nombre,descripcion,tipo,valor,codigo,
 fecha_inicio,fecha_fin,activa)

VALUES

(
    'Oferta de lanzamiento',
    '10 por ciento de descuento en hamburguesas seleccionadas',
    'descuento',10.00,NULL,
    '2026-01-01 00:00:00',
    '2027-12-31 23:59:59',
    TRUE
),

(
    'Bebidas 2x1',
    'Dos bebidas por el precio de una',
    '2x1',NULL,NULL,
    '2026-01-01 00:00:00',
    '2027-12-31 23:59:59',
    TRUE
),

(
    'Flex Queue especial',
    'Cinco euros de descuento en Flex Queue',
    'combo',5.00,NULL,
    '2026-01-01 00:00:00',
    '2027-12-31 23:59:59',
    TRUE
),

(
    'Codigo RIOT10',
    'Codigo promocional del 10 por ciento',
    'codigo',10.00,'RIOT10',
    '2026-01-01 00:00:00',
    '2027-12-31 23:59:59',
    TRUE
),

(
    'Codigo GAMER20',
    'Codigo promocional del 20 por ciento',
    'codigo',20.00,'GAMER20',
    '2026-01-01 00:00:00',
    '2027-12-31 23:59:59',
    TRUE
),

(
    'Oferta caducada',
    'Promocion caducada para pruebas',
    'codigo',10.00,'OLD2026',
    '2026-01-01 00:00:00',
    '2026-06-30 23:59:59',
    FALSE
);

-- ============================================================
-- 19. PRODUCTOS DE CADA OFERTA
-- ============================================================

INSERT INTO oferta_producto
(oferta_id,producto_id)

SELECT o.id,p.id

FROM ofertas o

JOIN productos p ON
(
    o.nombre='Oferta de lanzamiento'
    AND p.slug IN ('noxus','zaun','demacia')
)
OR
(
    o.nombre='Bebidas 2x1'
    AND p.slug IN ('coca-cola','agua')
)
OR
(
    o.nombre='Flex Queue especial'
    AND p.slug='flex-queue'
)
OR
(
    o.nombre='Codigo RIOT10'
    AND p.slug IN ('bind','2xko')
)
OR
(
    o.nombre='Codigo GAMER20'
    AND p.slug IN ('alistar','tft')
)
OR
(
    o.nombre='Oferta caducada'
    AND p.slug='spike'
);

-- ============================================================
-- 20. USUARIOS DE PRUEBA
-- ============================================================

-- Hashes bcrypt reales generados con PHP password_hash()
-- Admin123!
-- User123!

INSERT INTO usuarios
(nombre,email,password,telefono,direccion,
 fecha_nacimiento,rol)

VALUES

(
    'admin',
    'admin@rioteats.local',
    '$2y$12$1BSXuAxdTT7o1Wefne1DqOaEEqQ5K/dq2GNTg5dNxkx57svyep/Y6',
    '600111222',
    'Calle del Administrador 10, Barcelona',
    '1995-05-10',
    'admin'
),

(
    'user_1',
    'user_1@rioteats.local',
    '$2y$12$y21TMfgZenHdwmHHrgHERerfYcPPPPeCYifJ48f3Nv7IDYXiWWhFu',
    '600333444',
    'Avenida del Gamer 25, Barcelona',
    '2001-08-15',
    'usuario'
);

-- ============================================================
-- 21. PEDIDOS DE PRUEBA
-- ============================================================

-- ADMIN 1:
-- ENTREGADO / PAGADO / OFERTA 10%

INSERT INTO pedidos
(usuario_id,estado,fecha_pedido,subtotal,descuento,total,
 metodo_pago,estado_pago,oferta_id,codigo_oferta,referencia_prueba)

VALUES
(
    (SELECT id FROM usuarios WHERE nombre='admin'),
    'entregado','2026-10-20 14:30:00',
    18.70,1.19,17.51,
    'tarjeta','pagado',
    (SELECT id FROM ofertas WHERE nombre='Oferta de lanzamiento'),
    NULL,'ADM-001'
);

-- ADMIN 2:
-- CANCELADO / PAGO FALLIDO / SIN OFERTA

INSERT INTO pedidos
(usuario_id,estado,fecha_pedido,subtotal,descuento,total,
 metodo_pago,estado_pago,referencia_prueba)

VALUES
(
    (SELECT id FROM usuarios WHERE nombre='admin'),
    'cancelado','2026-10-21 20:15:00',
    18.40,0.00,18.40,
    'tarjeta','fallido','ADM-002'
);

-- ADMIN 3:
-- PREPARANDO / PAGO PENDIENTE

INSERT INTO pedidos
(usuario_id,estado,fecha_pedido,subtotal,descuento,total,
 metodo_pago,estado_pago,referencia_prueba)

VALUES
(
    (SELECT id FROM usuarios WHERE nombre='admin'),
    'preparando','2026-10-29 13:00:00',
    24.30,0.00,24.30,
    'simulado','pendiente','ADM-003'
);

-- ADMIN 4:
-- ENTREGADO / PAGADO / OFERTA COMBO

INSERT INTO pedidos
(usuario_id,estado,fecha_pedido,subtotal,descuento,total,
 metodo_pago,estado_pago,oferta_id,referencia_prueba)

VALUES
(
    (SELECT id FROM usuarios WHERE nombre='admin'),
    'entregado','2026-10-25 21:00:00',
    75.50,5.00,70.50,
    'tarjeta','pagado',
    (SELECT id FROM ofertas WHERE nombre='Flex Queue especial'),
    'ADM-004'
);

-- USER 1:
-- ENTREGADO / PAGADO / SIN OFERTA

INSERT INTO pedidos
(usuario_id,estado,fecha_pedido,subtotal,descuento,total,
 metodo_pago,estado_pago,referencia_prueba)

VALUES
(
    (SELECT id FROM usuarios WHERE nombre='user_1'),
    'entregado','2026-10-18 15:30:00',
    18.80,0.00,18.80,
    'efectivo','pagado','USR-001'
);

-- USER 2:
-- CONFIRMADO / PAGADO / CUPON GAMER20

INSERT INTO pedidos
(usuario_id,estado,fecha_pedido,subtotal,descuento,total,
 metodo_pago,estado_pago,oferta_id,codigo_oferta,referencia_prueba)

VALUES
(
    (SELECT id FROM usuarios WHERE nombre='user_1'),
    'confirmado','2026-10-28 19:45:00',
    31.80,6.36,25.44,
    'tarjeta','pagado',
    (SELECT id FROM ofertas WHERE codigo='GAMER20'),
    'GAMER20','USR-002'
);

-- USER 3:
-- ENVIADO / PAGADO / SIN OFERTA

INSERT INTO pedidos
(usuario_id,estado,fecha_pedido,subtotal,descuento,total,
 metodo_pago,estado_pago,referencia_prueba)

VALUES
(
    (SELECT id FROM usuarios WHERE nombre='user_1'),
    'enviado','2026-10-29 20:00:00',
    17.90,0.00,17.90,
    'tarjeta','pagado','USR-003'
);

-- USER 4:
-- PENDIENTE / PAGO PENDIENTE

INSERT INTO pedidos
(usuario_id,estado,fecha_pedido,subtotal,descuento,total,
 metodo_pago,estado_pago,referencia_prueba)

VALUES
(
    (SELECT id FROM usuarios WHERE nombre='user_1'),
    'pendiente','2026-10-30 18:00:00',
    7.40,0.00,7.40,
    'simulado','pendiente','USR-004'
);

-- USER 5:
-- ENTREGADO / PAGADO / OFERTA 2X1

INSERT INTO pedidos
(usuario_id,estado,fecha_pedido,subtotal,descuento,total,
 metodo_pago,estado_pago,oferta_id,referencia_prueba)

VALUES
(
    (SELECT id FROM usuarios WHERE nombre='user_1'),
    'entregado','2026-10-26 12:30:00',
    5.00,2.50,2.50,
    'tarjeta','pagado',
    (SELECT id FROM ofertas WHERE nombre='Bebidas 2x1'),
    'USR-005'
);

-- ============================================================
-- 22. DETALLES DE PEDIDOS
-- ============================================================

-- ADM-001

INSERT INTO pedido_detalle
(pedido_id,producto_id,nombre_producto,precio_unitario,
 cantidad,subtotal,referencia_prueba)

VALUES
(
    (SELECT id FROM pedidos WHERE referencia_prueba='ADM-001'),
    (SELECT id FROM productos WHERE slug='noxus'),
    'Noxus',11.90,1,12.70,'ADM-001-01'
),
(
    (SELECT id FROM pedidos WHERE referencia_prueba='ADM-001'),
    (SELECT id FROM productos WHERE slug='patatas-fritas'),
    'Patatas fritas',3.50,1,3.50,'ADM-001-02'
),
(
    (SELECT id FROM pedidos WHERE referencia_prueba='ADM-001'),
    (SELECT id FROM productos WHERE slug='coca-cola'),
    'Coca-Cola',2.50,1,2.50,'ADM-001-03'
);

-- ADM-002

INSERT INTO pedido_detalle
(pedido_id,producto_id,nombre_producto,precio_unitario,
 cantidad,subtotal,referencia_prueba)

VALUES
(
    (SELECT id FROM pedidos WHERE referencia_prueba='ADM-002'),
    (SELECT id FROM productos WHERE slug='zaun'),
    'Zaun',12.90,1,12.90,'ADM-002-01'
),
(
    (SELECT id FROM pedidos WHERE referencia_prueba='ADM-002'),
    (SELECT id FROM productos WHERE slug='nuggets'),
    'Nuggets',5.50,1,5.50,'ADM-002-02'
);

-- ADM-003

INSERT INTO pedido_detalle
(pedido_id,producto_id,nombre_producto,precio_unitario,
 cantidad,subtotal,referencia_prueba)

VALUES
(
    (SELECT id FROM pedidos WHERE referencia_prueba='ADM-003'),
    (SELECT id FROM productos WHERE slug='solo-queue'),
    'Solo Queue',17.90,1,17.90,'ADM-003-01'
),
(
    (SELECT id FROM pedidos WHERE referencia_prueba='ADM-003'),
    (SELECT id FROM productos WHERE slug='coca-cola'),
    'Coca-Cola',2.50,1,2.50,'ADM-003-02'
),
(
    (SELECT id FROM pedidos WHERE referencia_prueba='ADM-003'),
    (SELECT id FROM productos WHERE slug='helado'),
    'Helado',3.90,1,3.90,'ADM-003-03'
);

-- ADM-004

INSERT INTO pedido_detalle
(pedido_id,producto_id,nombre_producto,precio_unitario,
 cantidad,subtotal,referencia_prueba)

VALUES
(
    (SELECT id FROM pedidos WHERE referencia_prueba='ADM-004'),
    (SELECT id FROM productos WHERE slug='flex-queue'),
    'Flex Queue',75.50,1,75.50,'ADM-004-01'
);

-- USR-001

INSERT INTO pedido_detalle
(pedido_id,producto_id,nombre_producto,precio_unitario,
 cantidad,subtotal,referencia_prueba)

VALUES
(
    (SELECT id FROM pedidos WHERE referencia_prueba='USR-001'),
    (SELECT id FROM productos WHERE slug='bind'),
    'Bind',13.50,1,13.80,'USR-001-01'
),
(
    (SELECT id FROM pedidos WHERE referencia_prueba='USR-001'),
    (SELECT id FROM productos WHERE slug='patatas-fritas'),
    'Patatas fritas',3.50,1,3.50,'USR-001-02'
),
(
    (SELECT id FROM pedidos WHERE referencia_prueba='USR-001'),
    (SELECT id FROM productos WHERE slug='agua'),
    'Agua',1.50,1,1.50,'USR-001-03'
);

-- USR-002

INSERT INTO pedido_detalle
(pedido_id,producto_id,nombre_producto,precio_unitario,
 cantidad,subtotal,referencia_prueba)

VALUES
(
    (SELECT id FROM pedidos WHERE referencia_prueba='USR-002'),
    (SELECT id FROM productos WHERE slug='alistar'),
    'Alistar',16.90,1,16.90,'USR-002-01'
),
(
    (SELECT id FROM pedidos WHERE referencia_prueba='USR-002'),
    (SELECT id FROM productos WHERE slug='tft'),
    'TFT',14.90,1,14.90,'USR-002-02'
);

-- USR-003

INSERT INTO pedido_detalle
(pedido_id,producto_id,nombre_producto,precio_unitario,
 cantidad,subtotal,referencia_prueba)

VALUES
(
    (SELECT id FROM pedidos WHERE referencia_prueba='USR-003'),
    (SELECT id FROM productos WHERE slug='solo-queue'),
    'Solo Queue',17.90,1,17.90,'USR-003-01'
);

-- USR-004

INSERT INTO pedido_detalle
(pedido_id,producto_id,nombre_producto,precio_unitario,
 cantidad,subtotal,referencia_prueba)

VALUES
(
    (SELECT id FROM pedidos WHERE referencia_prueba='USR-004'),
    (SELECT id FROM productos WHERE slug='patatas-fritas'),
    'Patatas fritas',3.50,1,3.50,'USR-004-01'
),
(
    (SELECT id FROM pedidos WHERE referencia_prueba='USR-004'),
    (SELECT id FROM productos WHERE slug='helado'),
    'Helado',3.90,1,3.90,'USR-004-02'
);

-- USR-005: DOS COCA-COLAS

INSERT INTO pedido_detalle
(pedido_id,producto_id,nombre_producto,precio_unitario,
 cantidad,subtotal,referencia_prueba)

VALUES
(
    (SELECT id FROM pedidos WHERE referencia_prueba='USR-005'),
    (SELECT id FROM productos WHERE slug='coca-cola'),
    'Coca-Cola',2.50,2,5.00,'USR-005-01'
);

-- ============================================================
-- 23. INGREDIENTES INCLUIDOS EN PEDIDOS
-- ============================================================

INSERT INTO pedido_detalle_ingrediente
(pedido_detalle_id,ingrediente_id,nombre_ingrediente,
 precio_extra,accion)

SELECT
pd.id,
i.id,
i.nombre,
0.00,
'incluido'

FROM pedido_detalle pd

JOIN pedidos pe ON pe.id=pd.pedido_id
JOIN producto_ingrediente pi ON pi.producto_id=pd.producto_id
JOIN ingredientes i ON i.id=pi.ingrediente_id

WHERE pe.referencia_prueba IN (
    'ADM-001',
    'ADM-002',
    'USR-001',
    'USR-002'
);

-- ============================================================
-- 24. INGREDIENTES AÑADIDOS Y ELIMINADOS
-- ============================================================

-- ADM-001: AÑADIR JALAPENOS A NOXUS

INSERT INTO pedido_detalle_ingrediente
(pedido_detalle_id,ingrediente_id,nombre_ingrediente,
 precio_extra,accion)

SELECT
pd.id,i.id,i.nombre,i.precio_extra,'añadido'

FROM pedido_detalle pd
JOIN ingredientes i ON i.nombre='Jalapenos'

WHERE pd.referencia_prueba='ADM-001-01';

-- ADM-002: QUITAR JALAPENOS DE ZAUN

INSERT INTO pedido_detalle_ingrediente
(pedido_detalle_id,ingrediente_id,nombre_ingrediente,
 precio_extra,accion)

SELECT
pd.id,i.id,i.nombre,0.00,'quitado'

FROM pedido_detalle pd
JOIN ingredientes i ON i.nombre='Jalapenos'

WHERE pd.referencia_prueba='ADM-002-01';

-- USR-001: QUITAR PEPINILLOS DE BIND

INSERT INTO pedido_detalle_ingrediente
(pedido_detalle_id,ingrediente_id,nombre_ingrediente,
 precio_extra,accion)

SELECT
pd.id,i.id,i.nombre,0.00,'quitado'

FROM pedido_detalle pd
JOIN ingredientes i ON i.nombre='Pepinillos'

WHERE pd.referencia_prueba='USR-001-01';

-- USR-001: AÑADIR TOMATE A BIND

INSERT INTO pedido_detalle_ingrediente
(pedido_detalle_id,ingrediente_id,nombre_ingrediente,
 precio_extra,accion)

SELECT
pd.id,i.id,i.nombre,i.precio_extra,'añadido'

FROM pedido_detalle pd
JOIN ingredientes i ON i.nombre='Tomate'

WHERE pd.referencia_prueba='USR-001-01';

-- ============================================================
-- 25. COMPROBACIONES DE PRECIOS
-- ============================================================

-- COMPROBAR QUE LOS PEDIDOS CUADRAN

SELECT
p.referencia_prueba,
p.subtotal,
COALESCE(SUM(pd.subtotal),0) AS subtotal_detalles,
p.descuento,
p.total,
p.subtotal-p.descuento AS total_calculado,

CASE
    WHEN p.subtotal=COALESCE(SUM(pd.subtotal),0)
     AND p.total=p.subtotal-p.descuento
    THEN 'CORRECTO'
    ELSE 'ERROR'
END AS comprobacion

FROM pedidos p

LEFT JOIN pedido_detalle pd ON pd.pedido_id=p.id

WHERE p.referencia_prueba IS NOT NULL

GROUP BY p.id

ORDER BY p.referencia_prueba;

-- ============================================================
-- 26. CONSULTAS GENERALES
-- ============================================================

SELECT * FROM usuarios ORDER BY id;

SELECT * FROM categorias ORDER BY id;

SELECT * FROM productos ORDER BY categoria_id,nombre;

SELECT * FROM ingredientes ORDER BY nombre;

SELECT * FROM producto_ingrediente ORDER BY producto_id;

SELECT * FROM producto_combo ORDER BY combo_id;

SELECT * FROM ofertas ORDER BY id;

SELECT * FROM oferta_producto ORDER BY oferta_id;

SELECT * FROM pedidos ORDER BY fecha_pedido DESC;

SELECT * FROM pedido_detalle ORDER BY pedido_id;

SELECT * FROM pedido_detalle_ingrediente ORDER BY pedido_detalle_id;

-- ============================================================
-- 27. HISTORIAL DE PEDIDOS CON USUARIOS
-- ============================================================

SELECT
p.referencia_prueba,
u.nombre AS usuario,
u.rol,
p.estado,
p.estado_pago,
p.fecha_pedido,
p.subtotal,
p.descuento,
p.total,
o.nombre AS oferta,
p.codigo_oferta

FROM pedidos p

JOIN usuarios u ON u.id=p.usuario_id

LEFT JOIN ofertas o ON o.id=p.oferta_id

ORDER BY p.fecha_pedido DESC;

-- ============================================================
-- 28. RESUMEN DE REGISTROS
-- ============================================================

SELECT 'usuarios' tabla,COUNT(*) registros FROM usuarios
UNION ALL
SELECT 'categorias',COUNT(*) FROM categorias
UNION ALL
SELECT 'productos',COUNT(*) FROM productos
UNION ALL
SELECT 'ingredientes',COUNT(*) FROM ingredientes
UNION ALL
SELECT 'producto_ingrediente',COUNT(*) FROM producto_ingrediente
UNION ALL
SELECT 'producto_combo',COUNT(*) FROM producto_combo
UNION ALL
SELECT 'ofertas',COUNT(*) FROM ofertas
UNION ALL
SELECT 'oferta_producto',COUNT(*) FROM oferta_producto
UNION ALL
SELECT 'pedidos',COUNT(*) FROM pedidos
UNION ALL
SELECT 'pedido_detalle',COUNT(*) FROM pedido_detalle
UNION ALL
SELECT 'pedido_detalle_ingrediente',COUNT(*) FROM pedido_detalle_ingrediente;

-- ============================================================
-- FIN RIOT EATS
-- ============================================================
