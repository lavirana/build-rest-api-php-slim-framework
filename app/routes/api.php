<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once __DIR__ . '/../middlewares/jsonBodyParser.php';

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


// Get Player by id
$app->get('/players/{id}', function(Request $request, Response $response, array $args){
    $queryBuilder = $this->get('DB')->getQueryBuilder();

    $queryBuilder
        ->select('id', 'Name', 'Team', 'Category')
        ->from('Players')->where('id = ?')
        ->setParameter(0, $args['id']);
        $results = $queryBuilder->executeQuery()->fetchAllAssociative();

        $response->getBody()->write(json_encode($results));
        return $response->withHeader('content-type', 'application/json');
});


//Add Player
$app->post('/players/add', function(Request $request, Response $response){
   

    $parseBody = $request->getParsedBody();
    $queryBuilder = $this->get('DB')->getQueryBuilder();

    $queryBuilder
        ->insert('Players')
        ->values([
            'Name' => '?',
            'Team' => '?',
            'Category' => '?'
        ])
        ->setParameter(1, $parseBody['Name'])
        ->setParameter(2, $parseBody['Team'])
        ->setParameter(3, $parseBody['Category']);

    $result = $queryBuilder->executeStatement();

    $response->getBody()->write(json_encode($result));
    return $response->withHeader('content-type', 'application/json');

})->add($jsonBodyParser);



// Update Player by id


$app->put('/player/{id}', function(Request $request, Response $response, array $args) {
    
    $parsedBody = $request->getParsedBody();
    $queryBuilder = $this->get('DB')->getQueryBuilder();

    $queryBuilder
        ->update('Players')
        ->set('Name', '?')
        ->set('Team', '?')
        ->set('Category', '?')
        ->where('Id = ?')
        ->setParameter(1, $parsedBody['Name'])
        ->setParameter(2, $parsedBody['Team'])
        ->setParameter(3, $parsedBody['Category'])
        ->setParameter(4, $args['id'])
    ;

    $result = $queryBuilder->executeStatement();

    $response->getBody()->write(json_encode($result));
    return $response->withHeader('content-type', 'application/json');
    
  })->add($jsonBodyParser);


// Delete a Player based on id

$app->delete('/player/{id}', function(Request $request, Response $response, array $args) {
    $queryBuilder = $this->get('DB')->getQueryBuilder();

    $queryBuilder
        ->delete('Players')
        ->where('Id = ?')
        ->setParameter(0, $args['id'])
    ;

    $result = $queryBuilder->executeStatement();

    $response->getBody()->write(json_encode($result));
    return $response->withHeader('content-type', 'application/json');
    
  });