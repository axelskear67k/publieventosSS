-- Active: 1776866079393@@127.0.0.1@3306@inventario

CREATE DATABASE inventario;

USE inventario;

CREATE TABLE productos (
    IT INT AUTO_INCREMENT PRIMARY KEY,
    codigo CHAR(4) NOT NULL,
    cliente VARCHAR(100) NOT NULL,
    categoria ENUM('Vallas Publicitarias',
                   'Roll Screen',
                   'Paneletas Publicitarias',
                   'Total Led',
                   'Tricivallas') NOT NULL,
    ubicacion VARCHAR(150) NOT NULL,
    medida VARCHAR(50) NOT NULL,
    cantidad INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_termino DATE NOT NULL,
    estado ENUM('Disponible','Alquilado','No Disponible') NOT NULL
) ENGINE=INNODB;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50),
    password VARCHAR(50),
    rol VARCHAR(20)
);

INSERT INTO usuarios (usuario, password, rol) VALUES
('admin', '1234', 'admin'),
('edson', '1234', 'usuario');

ALTER TABLE productos 
MODIFY categoria ENUM(
'Vallas Publicitarias',
'Roll Screen',
'Paneletas Publicitarias',
'Total Led',
'Tricivallas'
);

INSERT INTO productos 
(codigo, cliente, categoria, ubicacion, medida, cantidad, fecha_inicio, fecha_termino, estado)
VALUES
('V001','CM MARTINES ICA','Roll Screen','Chincha','2m x 1m',1,'2026-03-01','2026-03-30','Disponible'),
('V002','NICOLINI FESTIVAL','Vallas Publicitarias','Chincha','5m x 3m',2,'2026-03-01','2026-03-30','Disponible'),
('V003','TODO IMPORTA','Paneletas Publicitarias','Chincha','3m x 2m',1,'2026-03-01','2026-03-30','Disponible'),
('V004','LEA','Total Led','Chincha','4m x 2m',1,'2026-03-01','2026-03-30','Disponible'),
('V005','CARLOS ALVAREZ','Tricivallas','Chincha','2m x 1m',1,'2026-03-01','2026-03-30','Disponible');

SELECT * FROM productos;
SELECT * FROM usuarios;