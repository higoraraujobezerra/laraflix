<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Models\Category;
use Core\UseCase\DTO\Category\CategoryInputDto;
use Core\UseCase\Category\DeleteCategoryUseCase;
use App\Repositories\Eloquent\CategoryEloquentRepository;

class DeleteCategoryUseCaseTest extends TestCase
{
    public function testExecute()
    {
        $model = new Category();
        $repository = new CategoryEloquentRepository($model);
        $useCase = new DeleteCategoryUseCase($repository);
        $categoryDb = Category::factory()->create();
        $input = new CategoryInputDto(
            id: $categoryDb->id
        );

        $useCase->execute($input);

        $this->assertSoftDeleted($categoryDb);
    }
}
