<?php

namespace Tests\Unit\UseCase\Category;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid as Uuid;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Category;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\CategoryCreateInputDto;
use Core\UseCase\DTO\Category\CategoryCreateOutputDto;

class CreateCategoryUseCaseUnitTest extends TestCase
{
    private $spy;
    private $mockEntity;
    private $mockInputDto;
    private $mockRepository;

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testCreateNewCategory()
    {
        $uuid = Uuid::uuid4()->toString();
        $categoryName = "Mock Name";

        $this->mockEntity = Mockery::mock(Category::class, [
            $uuid,
            $categoryName
        ]);
        $this->mockEntity->shouldReceive('id')->andReturn($uuid);

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('insert')->andReturn($this->mockEntity);

        $this->mockInputDto = Mockery::mock(CategoryCreateInputDto::class, [
            $uuid,
            $categoryName
        ]);

        $useCase = new CreateCategoryUseCase($this->mockRepository);
        $response = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryCreateOutputDto::class, $response);
        $this->assertEquals($uuid, $response->id);
        $this->assertEquals($categoryName, $response->name);
        $this->assertEquals('', $response->description);

        /**
         * Spies
         */

        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('insert')->andReturn($this->mockEntity);

        $useCase = new CreateCategoryUseCase($this->spy);
        $response = $useCase->execute($this->mockInputDto);

        $this->spy->shouldHaveReceived('insert');
    }
}
