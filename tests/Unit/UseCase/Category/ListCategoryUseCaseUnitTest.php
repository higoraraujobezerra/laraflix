<?php

namespace Tests\Unit\UseCase\Category;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid as Uuid;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Category;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDto;
use Core\UseCase\DTO\Category\CategoryOutputDto;
use Core\Domain\Repository\CategoryRepositoryInterface;

class ListCategoryUseCaseUnitTest extends TestCase
{
    private $spy;
    private $mockEntity;
    private $mockInputDto;
    private $mockRepository;

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testGetById()
    {
        $uuid = Uuid::uuid4()->toString();
        $categoryName = "Mock Name";

        $this->mockEntity = Mockery::mock(Category::class, [
            $uuid,
            $categoryName
        ]);
        $this->mockEntity->shouldReceive('id')->andReturn($uuid);
        $this->mockEntity->shouldReceive('createdAt')->andReturn('Y-m-d H:i:s');

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository
            ->shouldReceive('findById')
            ->with($uuid)
            ->andReturn($this->mockEntity);

        $this->mockInputDto = Mockery::mock(CategoryInputDto::class, [
            $uuid
        ]);

        $useCase = new ListCategoryUseCase($this->mockRepository);
        $response = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryOutputDto::class, $response);
        $this->assertEquals($uuid, $response->id);
        $this->assertEquals($categoryName, $response->name);
        $this->assertEquals('', $response->description);

        /**
         * Spies
         */

        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('findById')->andReturn($this->mockEntity);

        $useCase = new ListCategoryUseCase($this->spy);
        $response = $useCase->execute($this->mockInputDto);

        $this->spy->shouldHaveReceived('findById');
    }
}
