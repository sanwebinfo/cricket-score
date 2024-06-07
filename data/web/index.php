<?php

use Slim\Factory\AppFactory;
use DI\Container;
use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use Slim\Psr7\Factory\ResponseFactory;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate PHP-DI Container
$container = new Container();
AppFactory::setContainer($container);

// Instantiate the app
$app = AppFactory::create();

// Register routes
(require __DIR__ . '/../src/routes/routes.php')($app);

// Set base path
$app->setBasePath('/data/web');

// Add Error Handling Middleware and retrieve the instance
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Custom 404 Handler
$customNotFoundHandler = function (
    ServerRequestInterface $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) {
    $response = new Response();
    $data = [
        'error' => '404 Not Found',
        'message' => 'The page you are looking for could not be found.'
    ];
    $payload = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response
        ->withStatus(404)
        ->withHeader('Content-Type', 'application/json');
};

// Set the custom 404 handler
$errorMiddleware->setErrorHandler(HttpNotFoundException::class, $customNotFoundHandler);

// Custom Error Handler for other errors
$customErrorHandler = function (
    ServerRequestInterface $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) {
    $response = new Response();
    $data = [
        'error' => '500 Internal Server Error',
        'message' => 'An unexpected error has occurred.'
    ];
    if ($displayErrorDetails) {
        $data['details'] = $exception->getMessage();
    }
    $payload = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response
        ->withStatus(500)
        ->withHeader('Content-Type', 'application/json');
};

// Set the custom error handler for other errors
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

// Run app
$app->run();
