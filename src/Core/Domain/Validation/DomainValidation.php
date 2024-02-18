<?php

namespace Core\Domain\Validation;

use Core\Domain\Exception\EntityValidationException;

class DomainValidation
{
    public static function notNull(string $value, string $exceptMessage = null)
    {
        if (empty($value))
            throw new EntityValidationException($exceptMessage ?? 'Should not be empty or null');
    }

    public static function strMaxLenght(string $value, int $length = 255, string $exceptMessage = null)
    {
        if (strlen($value) > $length)
            throw new EntityValidationException($exceptMessage ?? 'The value must not be greater then {$length} characters');
    }

    public static function strMinLenght(string $value, int $length = 3, string $exceptMessage = null)
    {
        if (strlen($value) < $length)
            throw new EntityValidationException($exceptMessage ?? 'The value must be at least {$length} characters');
    }

    public static function strCanNullAndMaxLenght(string $value = '', int $length = 255, string $exceptMessage = null)
    {
        if (!empty($value) && strlen($value) > $length)
            throw new EntityValidationException($exceptMessage ?? 'The value must not be greater then {$length} characters');
    }
}