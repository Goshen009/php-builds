<?php

namespace App\CustomValidators;

use Doctrine\ORM\EntityManager;
use Laminas\Validator\AbstractValidator;

final class Unique extends AbstractValidator
{
    public const ERR_NOT_UNIQUE = 'unique';

    protected array $messageTemplates = [
        self::ERR_NOT_UNIQUE => "'%value%' has been used before in the database",
    ];

    protected EntityManager $entityManager;
    protected string $class;
    protected string $column;

    // I'll need the entity manager
    // And the class 
    // And then the parameter we're looking for

    public function __construct(EntityManager $entityManager, array $options = [])
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->class = $options['class'];
        $this->column = $options['column'];
    }

    public function isValid(mixed $value): bool
    {
        $this->setValue($value);

        echo $this->class . "\n";
        echo $this->column . "\n";
        echo $this->entityManager;

        die;

        // check and ensure that the class is a type of the Entity
        // check this in the constructor

        $hasValueBeenUsed = $this->entityManager->getRepository($this->class)
            ->findOneBy([$this->column => $value]) !== null;

        if ($hasValueBeenUsed) {
            $this->error(self::ERR_NOT_UNIQUE);
            return false;
        }

        // $this->error(self::NOT_IN_RANGE, [
        //     'min' => $this->min,
        //     'max' => $this->max,
        // ]);

        return true;
    }
}