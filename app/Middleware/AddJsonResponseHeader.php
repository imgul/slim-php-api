<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface  as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;

class AddJsonResponseHeader
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        return $handler->handle($request)
            ->withHeader('Content-Type', 'application/json');
    }
}
