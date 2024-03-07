<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Tests\TestCase;
use App\Models\Category as CategoryModel;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Entity\Category as CategoryEntity;
use Core\Domain\Repository\CategoryRepositoryInterface;
use App\Repositories\Eloquent\CategoryEloquentRepository;

class CategoryEloquentRepositoryTest extends TestCase
{
    protected $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new CategoryEloquentRepository(new CategoryModel());
    }

    public function testInsert()
    {
        $entity = new CategoryEntity(
            name: "Test insert"
        );

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(CategoryEntity::class, $response);
        $this->assertDatabaseHas('categories', ['name' => $entity->name]);
    }

    public function testFindById()
    {
        $category = CategoryModel::factory()->create();

        $response = $this->repository->findById($category->id);

        $this->assertInstanceOf(CategoryEntity::class, $response);
        $this->assertEquals($category->id, $response->id());
    }

    public function testFindByIdNotFound()
    {
        try {
            $response = $this->repository->findById("fakeUuid");
            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }

    public function testFindAll()
    {
        CategoryModel::factory()->count(10)->create();

        $response = $this->repository->findAll();

        $this->assertCount(10, $response);
    }

    public function testPaginate()
    {
        CategoryModel::factory()->count(100)->create();

        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(15, $response->items());
    }

    public function testPaginateEmpty()
    {
        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(0, $response->items());
    }

    public function testUpdate()
    {
        $categoryDb = CategoryModel::factory()->create();
        $categoryEntity = new CategoryEntity(
            id: $categoryDb->id,
            name: 'Updated Name'
        );

        $response = $this->repository->update($categoryEntity);

        $this->assertInstanceOf(CategoryEntity::class, $response);
        $this->assertNotEquals($categoryDb->name, $response->name);
        $this->assertEquals($categoryEntity->name, $response->name);
    }

    public function testUpdateIdNotFound()
    {
        try {
            $category = new CategoryEntity(name: 'Test');
            $response = $this->repository->update($category);
            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }
}
