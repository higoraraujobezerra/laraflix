<?php

namespace Tests\Unit\Domain\Entity;

use Throwable;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Category;
use Core\Domain\Exception\EntityValidationException;
use Ramsey\Uuid\Uuid as Uuid;

class CategoryUnitTest extends TestCase
{
    public function testAttrubute()
    {
        $category = new Category(
            name: 'New Dog',
            description: 'New desc',
            isActive: true
        );

        $this->assertNotEmpty($category->id());
        $this->assertEquals('New Dog', $category->name);
        $this->assertEquals('New desc', $category->description);
        $this->assertEquals(true, $category->isActive);
        $this->assertNotEmpty($category->createdAt());
    }

    public function testActivated()
    {
        $category = new Category(
            name: 'New Dog',
            isActive: false
        );

        $this->assertFalse($category->isActive);
        $category->activate();
        $this->assertTrue($category->isActive);
    }

    public function testDisabled()
    {
        $category = new Category(
            name: 'New Dog'
        );

        $this->assertTrue($category->isActive);
        $category->disable();
        $this->assertFalse($category->isActive);
    }

    public function testUpdate()
    {
        $uuid = (string) Uuid::uuid4()->toString();
        $category = new Category(
            id: $uuid,
            name: 'New Dog',
            description: 'New desc',
            isActive: true,
            createdAt: '2024-01-01 12:12:12'
        );

        $category->update(
            name: 'New Name',
            description: 'New desc'
        );

        $this->assertEquals($uuid, $category->id());
        $this->assertEquals('New Name', $category->name);
        $this->assertEquals('New desc', $category->description);
    }

    public function testExceptionName()
    {
        try {
            new Category(
                name: 'Ne',
                description: 'New desc'
            );

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }

    public function testExceptionDescription()
    {
        try {
            new Category(
                name: 'Name',
                description: random_bytes(999999)
            );

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }
}
