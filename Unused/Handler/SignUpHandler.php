<?php

declare(strict_types=1);

namespace App\Handler;

use App\DTO\SignUpDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\StringLength;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SignUpHandler implements RequestHandlerInterface
{
    public function __construct(
        private EntityManager $entityManager
    ) {
        
    }
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {// $userId = $request->getAttribute('user_id');
        $data = $request->getParsedBody();

        $user = new User();
        $signUpDTO = new SignUpDTO($data['username'], $data['email']);

        $user->signUp($signUpDTO);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse([
            'result' => 'Created a new User with Id ' . $user->getId()
        ]);
    }
}
