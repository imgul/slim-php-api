<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ContactFormSubmissionController
{
    final public function __construct()
    {}

    final public function store(Request $request, Response $response, array $data): Response
    {
        $body = $request->getParsedBody();
        $response->getBody()->write(json_encode($body));
        return $response;
    }
}