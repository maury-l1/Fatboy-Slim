<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$db = new SQLite3('../Slim/data/database.sqlite');

$app->get('/', function ($request, $response, $args) use ($db) {
    $result = $db->query('SELECT id, nom FROM musics');

    $html = "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <title>Lista de Artistas</title>
        <link rel='stylesheet' href='/css/styles.css'>
    </head>
    <body>
        <h1>Lista de Artistas</h1>
        <ul>";
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $html .= "<li><a href='/musico/" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['nom']) . "</a></li>";
    }

    $html .= "</ul></body></html>";

    $response->getBody()->write($html);
    return $response;
});

$app->get('/musico/{id}', function ($request, $response, $args) use ($db) {
    $id = $args['id'];
    $stmt = $db->prepare('SELECT * FROM musics WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $artist = $result->fetchArray(SQLITE3_ASSOC);

    if ($artist) {
        $html = "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <title>" . htmlspecialchars($artist['nom']) . "</title>
            <link rel='stylesheet' href='/css/styles.css'>
        </head>
        <body>
        <div class='artist-detail'>
            <h1>" . htmlspecialchars($artist['nom']) . "</h1>
            <p><strong>Biografía:</strong> " . htmlspecialchars($artist['biografia']) . "</p>
            <p><strong>Tipo:</strong> " . htmlspecialchars($artist['tipo']) . "</p>";

        if (!empty($artist['imagen'])) {
            $html .= "<img src='" . htmlspecialchars($artist['imagen']) . "' alt='" . htmlspecialchars($artist['nom']) . "'>";
        } else {
            $html .= "<p>No hay imagen disponible para este artista.</p>";
        }

        $html .= "<p><strong>Hits:</strong> " . htmlspecialchars($artist['hits']) . "</p>
        </div>
        </body>
        </html>";
    } else {
        $html = "<p>Artista no encontrado.</p>";
    }

    $response->getBody()->write($html);
    return $response;
});

$app->run();
