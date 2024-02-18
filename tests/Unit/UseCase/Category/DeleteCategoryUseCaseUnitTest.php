<?php

namespace Tests\Unit\UseCase\Category;

use Mockery;
use stdClass;
use Ramsey\Uuid\Uuid as Uuid;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Category;
use Core\UseCase\DTO\Category\CategoryInputDto;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryUpdateInputDto;
use Core\UseCase\DTO\Category\CategoryDeleteOutputDto;
use Core\UseCase\DTO\Category\CategoryUpdateOutputDto;
use Core\Domain\Repository\CategoryRepositoryInterface;

class DeleteCategoryUseCaseUnitTest extends TestCase
{
    private $spy;
    private $mockEntity;
    private $mockInputDto;
    private $mockRepository;

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testDeleteCategory()
    {
        $uuid = Uuid::uuid4()->toString();
        $categoryName = "Mock Name";
        $categoryDesc = "Mock desc";

        $this->mockEntity = Mockery::mock(Category::class, [
            $uuid, $categoryName, $categoryDesc
        ]);

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('delete')->andReturn(true);

        $this->mockInputDto = Mockery::mock(CategoryInputDto::class, [
            $uuid
        ]);

        $useCase = new DeleteCategoryUseCase($this->mockRepository);
        $response = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryDeleteOutputDto::class, $response);
        $this->assertTrue($response->success);

        /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('delete')->andReturn(true);

        $useCase = new DeleteCategoryUseCase($this->spy);
        $response = $useCase->execute($this->mockInputDto);

        $this->spy->shouldHaveReceived('delete');
    }

    public function testDeleteCategoryFalse()
    {
        $uuid = Uuid::uuid4()->toString();
        $categoryName = "Mock Name";
        $categoryDesc = "Mock desc";

        $this->mockEntity = Mockery::mock(Category::class, [
            $uuid, $categoryName, $categoryDesc
        ]);

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('delete')->andReturn(false);

        $this->mockInputDto = Mockery::mock(CategoryInputDto::class, [
            $uuid
        ]);

        $useCase = new DeleteCategoryUseCase($this->mockRepository);
        $response = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryDeleteOutputDto::class, $response);
        $this->assertFalse($response->success);

        /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('delete')->andReturn(true);

        $useCase = new DeleteCategoryUseCase($this->spy);
        $response = $useCase->execute($this->mockInputDto);

        $this->spy->shouldHaveReceived('delete');
    }
}
