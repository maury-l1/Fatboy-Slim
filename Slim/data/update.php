<?php
$db = new SQLite3('database.sqlite');

// Eliminar los duplicados manteniendo el primer registro
$db->exec("
DELETE FROM musics
WHERE id IN (1, 7, 8, 9, 10, 11);
");




echo "Duplicados eliminados.";
?>