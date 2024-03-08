<?php

namespace Tests\Feature\Core\UseCase\Category;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Core\UseCase\Category\ListCategoriesUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\DTO\Category\List\ListCategoriesInputDto;

class ListCategoriesUseCaseTest extends TestCase
{
    public function testExecute()
    {
        $categoriesDb = Category::factory()->count(20)->create();
        $useCase = $this->createUseCase();
        $input = new ListCategoriesInputDto();

        $response = $useCase->execute($input);

        $this->assertCount(15, $response->items);
        $this->assertEquals(count($categoriesDb), $response->total);
    }

    public function testExecuteListEmpty()
    {
        $useCase = $this->createUseCase();
        $input = new ListCategoriesInputDto();

        $response = $useCase->execute($input);

        $this->assertCount(0, $response->items);
    }

    private function createUseCase(): ListCategoriesUseCase
    {
        $model = new Category();
        $repository = new CategoryEloquentRepository($model);
        return new ListCategoriesUseCase($repository);
    }
}
