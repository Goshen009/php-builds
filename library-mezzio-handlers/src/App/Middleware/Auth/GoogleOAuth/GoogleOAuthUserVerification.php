<?php

declare(strict_types=1);

namespace App\Middleware\Auth\GoogleOAuth;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Google\Service\Oauth2\Userinfo;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GoogleOAuthUserVerification implements MiddlewareInterface
{
    public function __construct(
        private ProblemDetailsResponseFactory $problemDetailsFactory,
        private EntityManager $entityManager
    ){
        
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $userinfo = $request->getAttribute(UserInfo::class);

        if (is_null($userinfo->email)) {
            // a redirect response to try again... shouldn't normally happen either
            return $this->problemDetailsFactory->createResponse($request, 400, "There was an error retreiving your email, please try again");
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $userinfo->email]);
        
        if (is_null($user)) {
            $email = $userinfo->email;
            $username = $userinfo->name ?? '';

            $redirectUrl = "http://localhost:8080/users/register/google?email=$email&username=$username";
            return new RedirectResponse($redirectUrl);
        }

        $request = $request->withAttribute(User::class, $user);
        return $handler->handle($request);
    }
}
