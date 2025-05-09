<?php

// Crear o abrir la base de datos SQLite
$db = new SQLite3('database.sqlite');

// Crear la tabla 'musics' si no existe
$db->exec('
CREATE TABLE IF NOT EXISTS musics (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    biografia TEXT NOT NULL,
    tipo TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
');

// Insertar los valores actualizados en la tabla 'musics'
$db->exec("
INSERT INTO musics (nom, biografia, tipo) VALUES
('Pink Floyd', 'Banda británica de rock progresivo conocida por su innovadora música y por sus espectáculos en vivo.', 'Rock Progresivo'),
('Childish Gambino', 'Alias del músico, actor y productor Donald Glover, conocido por su estilo ecléctico que fusiona hip hop, R&B y funk.', 'Hip Hop / R&B'),
('Arctic Monkeys', 'Banda británica de rock alternativo formada en Sheffield, famosa por su estilo único y por sus éxitos en las listas de éxitos.', 'Rock Alternativo'),
('Héctor Lavoe', 'Cantante puertorriqueño de salsa, conocido como La Voz, uno de los más grandes exponentes del género.', 'Salsa'),
('Sade', 'Banda británica liderada por la cantante Sade Adu, conocida por su mezcla de jazz, soul y música pop suave.', 'Soul / Jazz');
");

echo "Base de datos y tabla 'musics' creadas e inicializadas con datos de ejemplo.";
?>
