<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';
//require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// App Routes
$app->get('/', function(Request $request, Response $response) {
    $response->getBody()->write("Welcome to the Slim PHP Application!");
    return $response;
});

$app->run();


