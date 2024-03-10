<?php

namespace Tests\Unit\App\Http\Controller\Api;

use Mockery;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use App\Http\Controllers\Api\CategoryController;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\List\ListCategoriesOutputDto;

class CategoryControllerUnitTest extends TestCase
{
    public function testIndex()
    {
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('get')->andReturn('');

        $mockDtoOutput = Mockery::mock(
            ListCategoriesOutputDto::class,
            [1, 1, 1, 1, 1, 1, 1, []]
        );

        $mockUseCase = Mockery::mock(ListCategoriesUseCase::class);
        $mockUseCase->shouldReceive('execute')->andReturn($mockDtoOutput);

        $controller = new CategoryController();
        $response = $controller->index($mockRequest, $mockUseCase);

        $this->assertIsObject($response);
        $this->assertArrayHasKey('meta', $response->additional);

        /**
         * Spies
         */
        $mockSpyUseCase = Mockery::spy(ListCategoriesUseCase::class);
        $mockSpyUseCase->shouldReceive('execute')->andReturn($mockDtoOutput);

        $controller = new CategoryController();
        $controller->index($mockRequest, $mockSpyUseCase);
        $mockSpyUseCase->shouldHaveReceived('execute');
    }
}
