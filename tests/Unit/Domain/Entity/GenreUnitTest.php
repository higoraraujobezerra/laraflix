<?php

namespace Tests\Unit\Domain\Entity;

use DateTime;
use Core\Domain\Entity\Genre;
use PHPUnit\Framework\TestCase;
use Core\Domain\ValueObject\UUID;
use Ramsey\Uuid\Uuid as RamseyUuid;

class GenreUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $date = new DateTime(date('Y-m-d H:i:s'));
        $genre = new Genre(
            id: new UUID($uuid),
            name: "Genre",
            isActive: true,
            createdAt: $date
        );

        $this->assertEquals($uuid, $genre->id());
        $this->assertEquals("Genre", $genre->name);
        $this->assertEquals(true, $genre->isActive);
        $this->assertEquals($date, $genre->createdAt());
    }

    public function testAttributeCrete()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $date = new DateTime(date('Y-m-d H:i:s'));
        $genre = new Genre(
            name: "Genre"
        );

        $this->assertNotEmpty($genre->id());
        $this->assertEquals("Genre", $genre->name);
        $this->assertEquals(true, $genre->isActive);
        $this->assertNotEmpty($genre->createdAt());
    }

    public function testActivated()
    {
        $genre = new Genre(
            name: 'Genre',
            isActive: false
        );

        $this->assertFalse($genre->isActive);
        $genre->activate();
        $this->assertTrue($genre->isActive);
    }

    public function testDisabled()
    {
        $genre = new Genre(
            name: 'Genre'
        );

        $this->assertTrue($genre->isActive);
        $genre->disable();
        $this->assertFalse($genre->isActive);
    }

    public function testUpdate()
    {
        $uuid =  new UUID(RamseyUuid::uuid4());
        $genre = new Genre(
            id: $uuid,
            name: 'Genre'
        );

        $genre->update(
            name: 'New Name'
        );

        $this->assertEquals($uuid, $genre->id());
        $this->assertEquals('New Name', $genre->name);
    }
}
