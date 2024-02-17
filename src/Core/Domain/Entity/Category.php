<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\UUID;

class Category
{
    use MethodsMagicsTrait;

    public function __construct(
        protected UUID|string $id = '',
        protected string $name = '',
        protected string $description = '',
        protected bool $isActive = true
    ) {
        $this->id = $this->id ? new UUID($this->id) : UUID::random();
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

    public function validate()
    {
        DomainValidation::strMaxLenght($this->name);
        DomainValidation::strMinLenght($this->name);
        DomainValidation::strCanNullAndMaxLenght($this->description);
    }
}
