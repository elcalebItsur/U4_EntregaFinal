--
-- Base de datos: `textisur`
--
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `telefono` VARCHAR(20) NOT NULL,
  `genero` VARCHAR(20),
  `fecha_nacimiento` DATE,
  `tipo` VARCHAR(20) NOT NULL,
  `nombre_tienda` VARCHAR(100),
  `rfc` VARCHAR(20),
  `direccion` VARCHAR(255),
  `fecha_registro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
