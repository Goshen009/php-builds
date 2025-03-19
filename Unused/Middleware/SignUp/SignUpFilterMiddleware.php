<?php

declare(strict_types=1);

namespace App\Middleware\SignUp;

use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\Regex;
use Laminas\Validator\StringLength;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SignUpFilterMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $data = $request->getParsedBody();

        $inputFilter = new InputFilter();
        $inputFilter->add([
            'name' => 'username',
            'required' => true,
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 3,
                        'max' => 50
                    ],
                ],
                [
                    'name' => Regex::class,
                    'options' => [
                        'pattern' => '/^[a-zA-Z\s]+$/', // Disallow numbers & special chars
                        'message' => 'Numbers and special characters are not allowed.'
                    ]
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'email',
            'required' => true,
            'validators' => [
                [
                    'name' => 'EmailAddress'
                ],
            ],
        ]);

        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return new JsonResponse([
                'error' => 'Invalid request data',
                'details' => $inputFilter->getMessages()
            ], 400);
        }

        $response = $handler->handle($request);
        return $response;
    }
}
