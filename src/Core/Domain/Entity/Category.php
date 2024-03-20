<?php

namespace Core\Domain\Entity;

use DateTime;
use Core\Domain\ValueObject\UUID;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\Entity\Traits\MethodsMagicsTrait;

class Category
{
    use MethodsMagicsTrait;

    public function __construct(
        protected UUID|string $id = '',
        protected string $name = '',
        protected string $description = '',
        protected bool $isActive = true,
        protected DateTime|string $createdAt = ''
    ) {
        $this->id = $this->id ? new UUID($this->id) : UUID::random();
        $this->createdAt = $this->createdAt ? new DateTime($this->createdAt) : new DateTime();
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

    public function update(string $name, string $description = '')
    {
        $this->name = $name;
        $this->description = $description;
        $this->validate();
    }

    private function validate()
    {
        DomainValidation::strMaxLenght($this->name);
        DomainValidation::strMinLenght($this->name);
        DomainValidation::strCanNullAndMaxLenght($this->description);
    }
}
