<?php

namespace Tests\Unit\UseCase\Category;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid as Uuid;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Category;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryUpdateInputDto;
use Core\UseCase\DTO\Category\CategoryUpdateOutputDto;
use Core\Domain\Repository\CategoryRepositoryInterface;

class UpdateCategoryUseCaseUnitTest extends TestCase
{
    private $spy;
    private $mockEntity;
    private $mockInputDto;
    private $mockRepository;

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testRenameCategory()
    {
        $uuid = Uuid::uuid4()->toString();
        $categoryName = "Mock Name";
        $categoryDesc = "Mock desc";

        $this->mockEntity = Mockery::mock(Category::class, [
            $uuid, $categoryName, $categoryDesc
        ]);
        $this->mockEntity->shouldReceive('update');

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('findById')->andReturn($this->mockEntity);
        $this->mockRepository->shouldReceive('update')->andReturn($this->mockEntity);

        $this->mockInputDto = Mockery::mock(CategoryUpdateInputDto::class, [
            $uuid,
            $categoryName
        ]);

        $useCase = new UpdateCategoryUseCase($this->mockRepository);
        $response = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryUpdateOutputDto::class, $response);

        /**
         * Spies
         */

        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('findById')->andReturn($this->mockEntity);
        $this->spy->shouldReceive('update')->andReturn($this->mockEntity);

        $useCase = new UpdateCategoryUseCase($this->spy);
        $response = $useCase->execute($this->mockInputDto);

        $this->spy->shouldHaveReceived('findById');
        $this->spy->shouldHaveReceived('update');
    }
}
