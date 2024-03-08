<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Models\Category;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDto;
use App\Repositories\Eloquent\CategoryEloquentRepository;

class ListCategoryUseCaseTest extends TestCase
{
    public function testExecute()
    {
        $model = new Category();
        $repository = new CategoryEloquentRepository($model);
        $useCase = new ListCategoryUseCase($repository);
        $categoryDb = Category::factory()->create();
        $input = new CategoryInputDto(
            id: $categoryDb->id
        );

        $response = $useCase->execute($input);

        $this->assertEquals($categoryDb->id, $response->id);
        $this->assertEquals($categoryDb->name, $response->name);
        $this->assertEquals($categoryDb->description, $response->description);
        $this->assertEquals($categoryDb->is_active, $response->is_active);
    }
}
