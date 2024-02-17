<?php

namespace Tests\Unit\Domain\Validation;

use Core\Domain\Exception\EntityValidationException;
use Throwable;
use PHPUnit\Framework\TestCase;
use Core\Domain\Validation\DomainValidation;

class DomainValidationUnitTest extends TestCase
{
    public function testNotNull()
    {
        try {
            $value = '';
            DomainValidation::notNull($value);

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }

    public function testNotNullCustomMessageException()
    {
        try {
            $value = '';
            DomainValidation::notNull($value, "Exception");

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, "Exception");
        }
    }

    public function testStrMaxLength()
    {
        try {
            $value = 'Test';
            DomainValidation::strMaxLenght($value, 3, 'Custom message');

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'Custom message');
        }
    }

    public function testStrMinLength()
    {
        try {
            $value = 'Test';
            DomainValidation::strMinLenght($value, 8, 'Custom message');

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'Custom message');
        }
    }

    public function testCanNullAndMaxLenght()
    {
        try {
            $value = 'Test';
            DomainValidation::strCanNullAndMaxLenght($value, 3, 'Custom message');

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'Custom message');
        }
    }
}
