<?php

use App\Controllers\CFController;
use App\Database\DB;
use App\Middleware\AddJsonResponseHeader;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

define("APP_ROOT", dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$builder = new ContainerBuilder;
$container = $builder->addDefinitions(APP_ROOT . '/config/database.php')->build();
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addBodyParsingMiddleware();

// allow cors for the localhost:4200
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', 'http://localhost:4200')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$error_middleware = $app->addErrorMiddleware(true, true, true);
$error_handler = $error_middleware->getDefaultErrorHandler();
$error_handler->forceContentType('application/json');

$app->add(new AddJsonResponseHeader);

$app->group('/api', function (RouteCollectorProxy $group) {

    // check api status
    $group->get('/api-status', function ($request, $response) {
        $response->getBody()->write(json_encode([
            'status' => 'OK',
            'message' => 'API is running'
        ]));
        return $response;
    });

    // check DB connection
    $group->get('/db-status', function ($request, $response) {
        $db = new DB(
            'localhost',
            'easecloud_api',
            'easecloud_api',
            'easecloud_api'
        );
        $status = $db->checkConnection();
        $response->getBody()->write(json_encode($status));
        return $response;
    });

    $group->get('/cf-submissions', [CFController::class, 'index']);
    $group->post('/submit-contact-form', [CFController::class, 'store']);
});

$app->run();
