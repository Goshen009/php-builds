<?php

declare(strict_types=1);

namespace App\Middleware;

use Laminas\InputFilter\InputFilter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FilterMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $response = $handler->handle($request);
        return $response;
    }

    // private function getValidators(...$params): array {
    //     return [
    //         ''
    //     ]
    // }

    // private function getInputFilter(): InputFilter {
    //     return [
    //         'username' => 
    //     ];



    //     $inputFilter = new InputFilter();
    //     $inputFilter->add([
    //         'name' => 'username',
    //         'required' => true,
    //         'validators' => [
    //             [
    //                 'name' => StringLength::class,
    //                 'options' => [
    //                     'min' => 3,
    //                     'max' => 50
    //                 ],
    //             ],
    //             [
    //                 'name' => Regex::class,
    //                 'options' => [
    //                     'pattern' => '/^[a-zA-Z\s]+$/', // Disallow numbers & special chars
    //                     'message' => 'Numbers and special characters are not allowed.'
    //                 ]
    //             ],
    //         ],
    //     ]);
    //     $inputFilter->add([
    //         'name' => 'email',
    //         'required' => true,
    //         'validators' => [
    //             [
    //                 'name' => 'EmailAddress'
    //             ],
    //         ],
    //     ]);
    // }
}
