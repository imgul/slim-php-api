<?php

namespace App\Controllers;

use App\Repositories\CFRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Valitron\Validator;

class CFController
{
    private Validator $validator;
    private $repository;

    final public function __construct(CFRepository $repository, Validator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;

        $this->validator->mapFieldsRules([
            'name' => ['required'],
            'email' => ['required'],
            'message' => ['required']
        ]);
    }

    final public function index(Request $request, Response $response, array $data): Response
    {
        $cf_submissions = $this->repository->getAll();

        $response->getBody()->write(json_encode([
            'status' => 'success',
            'message' => 'All contacts form submissions retrieved successfully',
            'data' => [
                'cfSubmissions' => $cf_submissions
            ]
        ]));

        return $response->withStatus(200);
    }

    final public function store(Request $request, Response $response, array $data): Response
    {
        try {
            $body = $request->getParsedBody();

            $this->validator = $this->validator->withData($body);

            if  (!$this->validator->validate()) {
                $response->getBody()->write(json_encode([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $this->validator->errors()
                ]));
                return $response->withStatus(422);
            }

            // Get the IP address of the user
            $ip = $_SERVER['REMOTE_ADDR'];
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            $body['ip'] = $ip ?? 'Error';

            // Get the user agent of the user
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Error';
            $body['user_agent'] = $user_agent;

            // Get the referrer of the user
            $http_host = $_SERVER['HTTP_HOST'] ?? 'Error';
            $body['http_host'] = $http_host;

            // Get the request_uri
            $request_uri = $_SERVER['REQUEST_URI'] ?? 'Error';
            $body['request_uri'] = $request_uri;

//            dd($body);

            $cf_submission_id = $this->repository->create($body);

            $response->getBody()->write(json_encode([
                'status' => 'success',
                'message' => 'Contact data received successfully',
                'data' => [
                    'id' => $cf_submission_id
                ]
            ]));

            return $response->withStatus(201);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'An error occurred while processing the request',
                'errors' => $e->getMessage()
            ]));
            return $response->withStatus(500);
        }
    }
}