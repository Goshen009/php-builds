<?php

declare(strict_types=1);

namespace App\Middleware\Validate;

use App\Entity\Book;
use App\Helpers;
use Assert\Assertion;
use Assert\InvalidArgumentException;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidImage implements MiddlewareInterface
{
    private const validExtensions = ['jpg', 'png', 'jpeg'];
    private const validMimeTypes = ['image/jpeg', 'image/png'];
    private const maxSize = 2 * 1024 * 1024;

    public function __construct(
        private ProblemDetailsResponseFactory $problemDetailsFactory,
    ) {

    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $files = $request->getUploadedFiles();

        try {
            Assertion::notEmptyKey($files, 'image', 'image is required');
            $image = $files['image'];

            $uploadError = $image->getError();
            Assertion::eq($uploadError, UPLOAD_ERR_OK, Helpers::getFileUploadErrorMessage($uploadError));

            $filename = $image->getClientFilename();
            $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
            Assertion::inArray($fileExtension, self::validExtensions, "the valid extensions are " . implode(', ', self::validExtensions));

            $fileMimeType = $image->getClientMediaType();
            Assertion::inArray($fileMimeType, self::validMimeTypes, "the valid MIME types are " . implode(', ', self::validMimeTypes));

            $fileSize = $image->getSize();
            Assertion::max($fileSize, self::maxSize, 'this file is larger than 2MB');

            return $handler->handle($request);
            
        } catch (InvalidArgumentException $e) {
            return $this->problemDetailsFactory->createResponse(
                $request,
                400,
                $e->getMessage()
            );
        }
    }
}
