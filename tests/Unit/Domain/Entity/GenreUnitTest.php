<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Genre;
use PHPUnit\Framework\TestCase;
use Core\Domain\ValueObject\UUID;
use DateTime;
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
}
