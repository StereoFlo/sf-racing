<?php

declare(strict_types = 1);

namespace App\Common\Domain\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Exception;

abstract class AbstractEntity
{
    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    protected $createdAt;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    protected $updatedAt;

    /**
     * @ORM\PrePersist()
     *
     * @throws Exception
     */
    public function setCreated(): void
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * @ORM\PreUpdate()
     *
     * @throws Exception
     */
    public function setUpdated(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function formatDateToString(?DateTimeImmutable $dateTimeImmutable, string $format = 'Y-m-d H:i:s'): ?string
    {
        if (empty($dateTimeImmutable)) {
            return null;
        }

        return $dateTimeImmutable->format($format);
    }
}
