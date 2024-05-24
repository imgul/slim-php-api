<?php

use App\Controllers\CFController;
use App\Database\DB;
use App\Middleware\AddJsonResponseHeader;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

define("APP_ROOT", dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$builder = new ContainerBuilder;
$container = $builder->addDefinitions(APP_ROOT . '/config/database.php')->build();
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addBodyParsingMiddleware();

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

    $group->post('/submit-contact-form', [CFController::class, 'store']);
});

$app->run();
