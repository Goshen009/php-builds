<?php

declare(strict_types=1);

namespace App\Middleware\Auth\GoogleOAuth;

use App\Services\GoogleOAuthService;
use Google\Service\Oauth2\Userinfo;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GoogleOAuthCallback implements MiddlewareInterface
{
    public function __construct(
        private ProblemDetailsResponseFactory $problemDetailsFactory,
        private GoogleOAuthService $googleOAuthService,
    ){
        
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $data = $request->getQueryParams();

        if (isset($data['error'])) {
            // would be a redirect response to another url but for now, just show a json
            return new JsonResponse("You did not allow Google to authenticate");
        }

        if (!isset($data['code'])) {
            // would also be a redirect response... shouldn't normally happen but welp
            return $this->problemDetailsFactory->createResponse($request, 400, "There was an error, please try again");
        }

        /** @var Google\Service\Oauth2\Userinfo */
        $userinfo = $this->googleOAuthService->getUserDataFromGoogle($data['code']);

        $request = $request->withAttribute(UserInfo::class, $userinfo);
        return $handler->handle($request);
    }
}
