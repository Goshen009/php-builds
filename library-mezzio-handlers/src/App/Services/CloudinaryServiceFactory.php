<?php
declare(strict_types=1);

namespace App\Services;

use Cloudinary\Cloudinary;
use Psr\Container\ContainerInterface;

class CloudinaryServiceFactory {
    public function __invoke(ContainerInterface $container): CloudinaryService {
        $config = $container->get('config')['cloudinary'];

        return new CloudinaryService(
            new Cloudinary($config)
        );
    }
}
