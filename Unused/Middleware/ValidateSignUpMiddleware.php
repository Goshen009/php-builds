<?php

declare(strict_types=1);

namespace App\Middleware;

use App\CustomValidators\Unique;
use App\Entity\User;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Filter\StringTrim;
use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\Regex;
use Laminas\Validator\StringLength;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidateSignUpMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $data = $request->getParsedBody();

        $username = $this->getUsernameInputFilter();
        $email = $this->getEmailInputFilter();

        $inputFilter = new InputFilter();
        $inputFilter->add($username);
        $inputFilter->add($email);

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

    private function getEmailInputFilter(): Input {
        $email = new Input("email");
        $email->setRequired(true);
        $email->getValidatorChain()->attach(new EmailAddress());

        return $email;
    }

    private function getUsernameInputFilter(): Input {
        $username = new Input("username");
        $username->setRequired(true);

        $username->getFilterChain()->attach(new StringTrim());

        $username->getValidatorChain()
            ->attach(new StringLength(['min' => 3, 'max' => 50]))
            ->attach(new Regex([
                'pattern' => '/^[a-zA-Z\s]+$/',
                'message' => 'Numbers and special characters are not allowed.'
            ]))
            ->attach(new Unique([
                'class' => User::class,
                'column' => 'username'
            ]));

        return $username;
    }
}
