<?php

namespace Tests\Unit\Domain\Entity;

use Throwable;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Category;
use Core\Domain\Exception\EntityValidationException;

class CategoryUnitTest extends TestCase
{
    public function testAttrubute()
    {
        $category = new Category(
            name: 'New Dog',
            description: 'New desc',
            isActive: true
        );

        $this->assertEquals('New Dog', $category->name);
        $this->assertEquals('New desc', $category->description);
        $this->assertEquals(true, $category->isActive);
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
        $uuid = 'uuid.value';
        $category = new Category(
            id: $uuid,
            name: 'New Dog',
            description: 'New desc',
            isActive: true
        );

        $category->update(
            name: 'New Name',
            description: 'New desc'
        );

        $this->assertEquals('New Name', $category->name);
        $this->assertEquals('New desc', $category->description);
    }

    public function testExceptionName()
    {
        try {
            $category = new Category(
                name: 'Ne',
                description: 'New desc'
            );

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }
}
