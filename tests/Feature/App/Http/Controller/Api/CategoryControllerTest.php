<?php

namespace Tests\Feature\App\Http\Controller\Api;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Foundation\Testing\WithFaker;
use Core\UseCase\Category\ListCategoryUseCase;
use App\Http\Controllers\Api\CategoryController;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\Category\ListCategoriesUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryControllerTest extends TestCase
{
    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        $model = new Category();
        $this->repository = new CategoryEloquentRepository($model);

        $this->controller = new CategoryController();

        parent::setUp();
    }
    public function testIndex()
    {
        $useCase = new ListCategoriesUseCase($this->repository);
        $response = $this->controller->index(
            new Request(),
            $useCase
        );

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);
    }

    public function testStore()
    {
        $useCase = new CreateCategoryUseCase($this->repository);
        $request = new StoreCategoryRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'Test'
        ]));

        $response = $this->controller->store($request, $useCase);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->status());
    }

    public function testShow()
    {
        $categoryDb = Category::factory()->create();
        $response = $this->controller->show(
            useCase: new ListCategoryUseCase($this->repository),
            id: $categoryDb->id
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
    }

    public function testUpdate()
    {
        $categoryDb = Category::factory()->create();
        $request = new UpdateCategoryRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'Updated'
        ]));

        $response = $this->controller->update(
            request: $request,
            useCase: new UpdateCategoryUseCase($this->repository),
            id: $categoryDb->id
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
    }
}
