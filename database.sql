-- Base de datos del taller monolítico: Gestor de historias de usuario y sprints
-- Importar desde phpMyAdmin (pestaña SQL o Importar), sin seleccionar ninguna base.

CREATE DATABASE IF NOT EXISTS gestor_historias
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestor_historias;

DROP TABLE IF EXISTS historias;
DROP TABLE IF EXISTS sprints;

CREATE TABLE sprints (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(100) NOT NULL,
    objetivo     VARCHAR(255) NOT NULL DEFAULT '',
    fecha_inicio DATE NOT NULL,
    fecha_fin    DATE NOT NULL
) ENGINE=InnoDB;

CREATE TABLE historias (
    id                 INT AUTO_INCREMENT PRIMARY KEY,
    titulo             VARCHAR(150) NOT NULL,
    descripcion        TEXT NOT NULL,
    responsable        VARCHAR(100) NOT NULL,
    prioridad          ENUM('alta','media','baja') NOT NULL DEFAULT 'media',
    estado             ENUM('nueva','activa','finalizada','impedimento') NOT NULL DEFAULT 'nueva',
    puntos             INT NOT NULL,
    fecha_creacion     DATE NOT NULL,
    fecha_finalizacion DATE NULL,
    sprint_id          INT NOT NULL,
    CONSTRAINT fk_historias_sprint FOREIGN KEY (sprint_id)
        REFERENCES sprints(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Datos de ejemplo
INSERT INTO sprints (nombre, objetivo, fecha_inicio, fecha_fin) VALUES
('Sprint 1', 'Acceso de usuarios al sistema', '2026-11-02', '2026-11-15'),
('Sprint 2', 'Tableros e informes de avance',  '2026-11-16', '2026-11-29');

INSERT INTO historias (titulo, descripcion, responsable, prioridad, estado, puntos, fecha_creacion, fecha_finalizacion, sprint_id) VALUES
('Registro de usuarios',   'Como visitante quiero registrarme para poder acceder al sistema.',        'Ana',    'alta',  'finalizada',  5, '2026-11-02', '2026-11-09', 1),
('Inicio de sesión',       'Como usuario quiero iniciar sesión para ver mis proyectos.',              'Carlos', 'alta',  'activa',      3, '2026-11-03', NULL,         1),
('Recuperar contraseña',   'Como usuario quiero recuperar mi contraseña si la olvido.',               'Ana',    'media', 'impedimento', 8, '2026-11-04', NULL,         1),
('Tablero de historias',   'Como líder quiero ver las historias por estado para seguir el avance.',   'Carlos', 'media', 'nueva',       8, '2026-11-16', NULL,         2),
('Informe por sprint',     'Como líder quiero un informe por sprint para presentar el avance.',       'Laura',  'baja',  'nueva',      13, '2026-11-16', NULL,         2);
