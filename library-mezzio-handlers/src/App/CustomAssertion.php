<?php

declare(strict_types=1);

namespace App;

use Assert\Assert;
use Assert\Assertion as BaseAssertion;
use Assert\InvalidArgumentException;
use Doctrine\ORM\EntityManager;

class CustomAssertion extends BaseAssertion
{
    public static function isUnique(string $value, EntityManager $entityManager, string $entityClass, string $field, string $message = "This is not a unique field"): void
    {
        $repo = $entityManager->getRepository($entityClass);
        $existing = $repo->findOneBy([$field => $value]);
        
        BaseAssertion::null($existing, $message);
    }

    public static function existsInDatabase(string $value, EntityManager $entityManager, string $entityClass, string $field, string $message = "This value does not exist in the database"): void
    {
        $repo = $entityManager->getRepository($entityClass);
        $existing = $repo->findOneBy([$field => $value]);

        BaseAssertion::notNull($existing, $message);
    }

    public static function validPassword(string $value): void
    {
        Assert::that($value)
            ->minLength(8, 'Password must be at least 8 characters long.')
            ->maxLength(64, 'Password must not exceed 64 characters.')
            ->regex('/[A-Z]/', 'Password must contain at least one uppercase letter.')
            ->regex('/[a-z]/', 'Password must contain at least one lowercase letter.')
            ->regex('/[\W]/', 'Password must contain at least one special character.')
            ->regex('/\d/', 'Password must contain at least one number.');
    }
}