<?php

use leona\crud\controllers\ControllerInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

require_once 'vendor\autoload.php';
require_once './src/config/Database.php';

$db = new Database("teste.sqlite3");

$rotaDaRequisicao = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:$_SERVER['REQUEST_URI'];
$rotas = require './src/routes/index.php';

if(!array_key_exists($rotaDaRequisicao, $rotas)) {
    http_response_code(404);
    exit();
}

$psr17Factory = new Psr17Factory(); 

$creator = new ServerRequestCreator(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);

$request = $creator->fromGlobals();

$classController = $rotas[$rotaDaRequisicao];
/**
 * @var ControllerInterface
 */
$controller = new $classController($db);

$response = $controller->processRequest($request);

foreach($response->getHeaders() as $name => $values) {
    foreach($values as $value) {
        header("$name: $value");
    }
}

echo $response->getBody();
