<?php

use App\Controllers\ContactFormSubmissionController;
use App\Middleware\AddJsonResponseHeader;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

define("APP_ROOT", dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$error_middleware = $app->addErrorMiddleware(true, true, true);
$error_handler = $error_middleware->getDefaultErrorHandler();
$error_handler->forceContentType('application/json');

$app->add(new AddJsonResponseHeader);

$app->group('/api', function (RouteCollectorProxy $group) {

    // check api status
    $group->get('/status', function ($request, $response) {
        $response->getBody()->write(json_encode([
            'status' => 'OK',
            'message' => 'API is running'
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->post('/submit-contact-form', [ContactFormSubmissionController::class, 'store']);
});

$app->run();
