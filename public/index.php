<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

$db = new SQLite3('../Slim/data/database.sqlite');

$app->get('/', function ($request, $response, $args) use ($db) {
    // Consulta para obtener todos los nombres de los artistas
    $result = $db->query('SELECT id, nom FROM musics');
    
    // Preparar el HTML para mostrar los enlaces
    $html = "<h1>Lista de Artistas</h1><ul>";
    
    // Recorrer los resultados y crear un enlace para cada artista
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $html .= "<li><a href='/musico/" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['nom']) . "</a></li>";
    }

    $html .= "</ul>";
    
    $response->getBody()->write($html);

    return $response;
});
$app->get('/musico/{id}', function ($request, $response, $args) use ($db) {
    $id = $args['id'];

    // Consulta para obtener la información detallada del artista
    $stmt = $db->prepare('SELECT * FROM musics WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    $artist = $result->fetchArray(SQLITE3_ASSOC);

    // Verificar si se encontró al artista
    if ($artist) {
        // Mostrar la información detallada del artista
        $html = "<h1>" . htmlspecialchars($artist['nom']) . "</h1>";
        $html .= "<p><strong>Biografía:</strong> " . htmlspecialchars($artist['biografia']) . "</p>";
        $html .= "<p><strong>Tipo:</strong> " . htmlspecialchars($artist['tipo']) . "</p>";
        
        // Mostrar la imagen del artista, si existe
        if (!empty($artist['imagen'])) {
            $html .= "<img src='" . htmlspecialchars($artist['imagen']) . "' alt='" . htmlspecialchars($artist['nom']) . "' style='width:200px; height:auto;'>";
        } else {
            $html .= "<p>No hay imagen disponible para este artista.</p>";
        }
        
        // Mostrar los títulos de las canciones más populares
        $html .= "<p><strong>Hits:</strong> " . htmlspecialchars($artist['hits']) . "</p>";
    } else {
        // Si no se encuentra el artista
        $html = "<p>Artista no encontrado.</p>";
    }
    
    // Escribir el HTML en el cuerpo de la respuesta
    $response->getBody()->write($html);

    return $response;
});



/*

$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

*/
$app->run();    
