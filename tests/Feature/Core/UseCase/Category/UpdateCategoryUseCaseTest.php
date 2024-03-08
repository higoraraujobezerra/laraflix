<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Models\Category;
use Core\UseCase\Category\UpdateCategoryUseCase;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\DTO\Category\Update\CategoryUpdateInputDto;

class UpdateCategoryUseCaseTest extends TestCase
{
    public function testExecute()
    {
        $model = new Category();
        $repository = new CategoryEloquentRepository($model);
        $useCase = new UpdateCategoryUseCase($repository);
        $categoryDb = Category::factory()->create();
        $input = new CategoryUpdateInputDto(
            id: $categoryDb->id,
            name: "New name"
        );

        $response = $useCase->execute($input);

        $this->assertEquals("New name", $response->name);
        $this->assertEquals($categoryDb->description, $response->description);
        $this->assertDatabaseHas('categories', ['name' => $response->name]);
    }
}
