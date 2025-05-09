<?php
$db = new SQLite3('database.sqlite');

// Eliminar los duplicados manteniendo el primer registro
$db->exec('
DELETE FROM musics 
WHERE id NOT IN (
    SELECT MIN(id) 
    FROM musics 
    GROUP BY nom
);
');

echo "Duplicados eliminados.";
?>