<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use DateTime;
use Core\Domain\ValueObject\UUID;

class Genre
{
    use MethodsMagicsTrait;

    public function __construct(
        protected string $name,
        protected ?UUID $id = null,
        protected bool $isActive = true,
        protected ?DateTime $createdAt = null
    ) {
        $this->id = $this->id ?? UUID::random();
        $this->createdAt = $this->createdAt ?? new DateTime();
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function disable(): void
    {
        $this->isActive = false;
    }
}
