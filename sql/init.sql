-- Daw2mvc — script de inicialización
-- Uso (MySQL/MariaDB):
--   mysql -u root < sql/init.sql
-- O desde phpMyAdmin: Importar este archivo.

CREATE DATABASE IF NOT EXISTS daw2mvc
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE daw2mvc;

DROP TABLE IF EXISTS camisetas;

CREATE TABLE camisetas (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  talla ENUM('XXL', 'XL', 'L', 'M', 'S') NOT NULL,
  precio DECIMAL(8, 2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO camisetas (nombre, talla, precio) VALUES
  ('Camiseta básica negra', 'M', 19.99),
  ('Camiseta técnica running', 'L', 29.50),
  ('Camiseta oversize', 'XL', 24.00);
