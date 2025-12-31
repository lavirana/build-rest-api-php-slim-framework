<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/db.php';

// All the API Routes

// Home

$app->get('/', function(Request $request, Response $response) {

    $response_array = [
        'message' => 'Welcome to the Slim PHP Application!'
    ];

    $response->getBody()->write(json_encode($response_array));

    return $response->withHeader('Content-Type', 'application/json');
});


// Get all Players 
$app->get('/players/',function(Request $request, Response $response){
    $queryBuilder = $this->get('DB')->getQueryBuilder();

    $queryBuilder
        ->select('id', 'Name', 'Team', 'Category')
        ->from('Players')
    ;
    
    //$results = $queryBuilder->executeQuery()->fetchAll();
    $results = $queryBuilder->executeQuery()->fetchAllAssociative();

    $response->getBody()->write(json_encode($results));
    return $response
            ->withHeader('content-type', 'application/json');
});





