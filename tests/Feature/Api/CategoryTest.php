<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Http\Response;

class CategoryTest extends TestCase
{
    protected $endpoint = 'api/categories';

    public function testEmptyListCategories()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    public function testListAllCategories()
    {
        Category::factory()->count(30)->create();
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
                'to',
                'from'
            ]
        ]);
        $response->assertJsonCount(15, 'data');
    }

    public function testListPaginateCategories()
    {
        Category::factory()->count(25)->create();
        $response = $this->getJson("$this->endpoint?page=2");

        $response->assertStatus(200);
        $this->assertEquals(2, $response['meta']['current_page']);
        $this->assertEquals(25, $response['meta']['total']);
        $response->assertJsonCount(10, 'data');
    }

    public function testListCategoryNotFound()
    {
        $response = $this->getJson("$this->endpoint/{fake_value}");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testListCategory()
    {
        $category = Category::factory()->create();
        $response = $this->getJson("$this->endpoint/{$category->id}");

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at'
            ]
        ]);

        $this->assertEquals($category->id, $response['data']['id']);
    }

    public function testValidationStore()
    {
        $data = [];
        $response = $this->postJson($this->endpoint, $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name'
            ]
        ]);
    }

    public function testStore()
    {
        $data = [
            'name' => 'Test'
        ];
        $response = $this->postJson($this->endpoint, $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at'
            ]
        ]);

        $data = [
            'name' => 'Test',
            'description' => 'Desc',
            'is_active' => false
        ];
        $response = $this->postJson($this->endpoint, $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals('Test', $response['data']['name']);
        $this->assertEquals('Desc', $response['data']['description']);
        $this->assertEquals(false, $response['data']['is_active']);
        $this->assertDatabaseHas('categories', [
            'id' => $response['data']['id'],
            'is_active' => false
        ]);
    }

    public function testNotFoundUpdate()
    {
        $data = [
            'name' => 'New name'
        ];

        $response = $this->putJson("{$this->endpoint}/{fake_id}", $data);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testValidationUpdate()
    {
        $data = [];
        $category = Category::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$category->id}", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name'
            ]
        ]);
    }

    public function testUpdate()
    {
        $data = [
            'name' => 'New name'
        ];
        $category = Category::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$category->id}", $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at'
            ]
        ]);
        $this->assertDatabaseHas('categories', [
            'id' => $response['data']['id'],
            'name' => 'New name'
        ]);
    }

    public function testNotFoundDelete()
    {
        $response = $this->deleteJson("{$this->endpoint}/{fake_id}");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDelete()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("{$this->endpoint}/{$category->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }
}
