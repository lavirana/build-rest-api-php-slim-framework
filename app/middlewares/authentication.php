<?php


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Response;

// Middleware function mein $app (ya $container) pass karein
$authentication = function (Request $request, RequestHandler $handler) use ($app) {
    $userName = $request->getHeaderLine('X-API-User');
    $apiKey = $request->getHeaderLine('X-API-Key');

    if ($userName === '' || $apiKey === '') {
        return sendErrorResponse('Unauthorized - Missing Credentials');
    }

    // Container se DB nikalne ka sahi tarika middleware ke andar
    $container = $app->getContainer();
    $queryBuilder = $container->get('DB')->getQueryBuilder();

    $queryBuilder
        ->select('ApiKey') // Column name check karein (K capital hai ya small?)
        ->from('Users')
        ->where('UserName = ?')
        ->setParameter(0, $userName); // Parameter index 0 se start karein agar Slim/Doctrine version naya hai

    // fetchAssociative use karein taki single row mile
    $userRow = $queryBuilder->executeQuery()->fetchAssociative();

    if (!$userRow) {
        return sendErrorResponse('User not found');
    }

    // Yahan $userRow['ApiKey'] direct check karein
    $hashedApiKey = $userRow['ApiKey'] ?? null;

    if (!$hashedApiKey) {
        return sendErrorResponse('ApiKey does not exist in DB');
    }
    // Password verify
   // if (!password_verify($apiKey, $hashedApiKey)) {
        if ($apiKey !== $hashedApiKey) {
        return sendErrorResponse('API Key is incorrect');
    }

    return $handler->handle($request);
};

// Error response function (Make sure ye reachable ho)
function sendErrorResponse($errorMsg) {
    $response = new Response();
    $response->getBody()->write(json_encode(['error' => $errorMsg]));
    return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
}