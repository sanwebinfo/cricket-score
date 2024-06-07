<?php

use Slim\App;
use App\Controllers\ScoreController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response, array $args) {
        $data = ['api' => 'Live Cricket Score API'];
        $payload = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('X-XSS-Protection', '1; mode=block')
            ->withHeader('X-Content-Type-Options', 'nosniff')
            ->withHeader('X-Frame-Options', 'DENY')
            ->withHeader('Strict-Transport-Security', 'max-age=63072000')
            ->withHeader('X-Robots-Tag', 'noindex, nofollow', true);
        
    });
    $app->get('/score', ScoreController::class . ':score');
};
