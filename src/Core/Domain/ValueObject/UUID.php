<?php

namespace Core\Domain\ValueObject;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class UUID
{
    public function __construct(
        protected string $value
    ) {
        $this->ensureIsValid($value);
    }

    public static function random(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function ensureIsValid(string $uuid)
    {
        if (!RamseyUuid::isValid($uuid))
            throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', static::class, $uuid));
    }
}
