<?php

namespace Core\Domain\Entity;

use DateTime;
use Core\Domain\ValueObject\UUID;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\Entity\Traits\MethodsMagicsTrait;

class Genre
{
    use MethodsMagicsTrait;

    protected array $categoriesId = [];

    public function __construct(
        protected string $name,
        protected ?UUID $id = null,
        protected bool $isActive = true,
        protected ?DateTime $createdAt = null
    ) {
        $this->id = $this->id ?? UUID::random();
        $this->createdAt = $this->createdAt ?? new DateTime();

        $this->validate();
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function disable(): void
    {
        $this->isActive = false;
    }

    public function update(string $name)
    {
        $this->name = $name;
        $this->validate();
    }

    public function addCategory(string $categoryId)
    {
        array_push($this->categoriesId, $categoryId);
    }

    private function validate()
    {
        DomainValidation::strMaxLength($this->name);
        DomainValidation::strMinLength($this->name);
    }
}
