<?php

declare(strict_types = 1);

namespace App\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
}
