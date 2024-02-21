<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Tests\TestCase;
use App\Models\Category as CategoryModel;
use Core\Domain\Entity\Category as CategoryEntity;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Repository\CategoryRepositoryInterface;

class CategoryEloquentRepositoryTest extends TestCase
{
    public function testInsert()
    {
        $repository = new CategoryEloquentRepository(new CategoryModel());
        $entity = new CategoryEntity(
            name: "Test insert"
        );

        $response = $repository->insert($entity);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $repository);
        $this->assertInstanceOf(CategoryEntity::class, $response);
        $this->assertDatabaseHas('categories', ['name' => $entity->name]);
    }
}
