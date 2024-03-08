<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Models\Category;
use Core\UseCase\Category\CreateCategoryUseCase;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\DTO\Category\Create\CategoryCreateInputDto;


class CreateCategoryUseCaseTest extends TestCase
{
    public function testExecute()
    {
        $model = new Category();
        $repository = new CategoryEloquentRepository($model);
        $useCase = new CreateCategoryUseCase($repository);
        $input = new CategoryCreateInputDto(
            name: 'Test'
        );
        $response = $useCase->execute($input);

        $this->assertEquals('Test', $response->name);
        $this->assertNotEmpty($response->id);
        $this->assertDatabaseHas('categories', ['id' => $response->id]);
    }
}
