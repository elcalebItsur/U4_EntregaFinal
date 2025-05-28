-- Script para PostgreSQL
-- Base de datos: textisur

CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    genero VARCHAR(20),
    fecha_nacimiento DATE,
    tipo VARCHAR(20) NOT NULL,
    nombre_tienda VARCHAR(100),
    rfc VARCHAR(20),
    direccion VARCHAR(255),
    foto_perfil VARCHAR(255), -- Nuevo campo para la foto de perfil
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS productos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio NUMERIC(10,2) NOT NULL,
    categoria VARCHAR(50),
    stock INT DEFAULT 0,
    vendedor_id INT REFERENCES usuarios(id),
    imagen VARCHAR(255), -- Nuevo campo para guardar el nombre de la imagen
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS carrito (
    usuario_id INT REFERENCES usuarios(id),
    producto_id INT REFERENCES productos(id),
    cantidad INT DEFAULT 1,
    PRIMARY KEY (usuario_id, producto_id)
);

CREATE TABLE IF NOT EXISTS favoritos (
    usuario_id INT REFERENCES usuarios(id),
    producto_id INT REFERENCES productos(id),
    PRIMARY KEY (usuario_id, producto_id)
);

-- Tabla de ventas
CREATE TABLE IF NOT EXISTS ventas (
    id SERIAL PRIMARY KEY,
    usuario_id INT REFERENCES usuarios(id),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de detalle de venta
CREATE TABLE IF NOT EXISTS detalle_venta (
    id SERIAL PRIMARY KEY,
    venta_id INT REFERENCES ventas(id) ON DELETE CASCADE,
    producto_id INT REFERENCES productos(id),
    cantidad INT NOT NULL,
    precio_unitario NUMERIC(10,2) NOT NULL,
    vendedor_id INT REFERENCES usuarios(id)
);
