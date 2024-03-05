<?php

namespace App\Repositories\Eloquent;

use App\Models\Category as CategoryModel;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Entity\Category as CategoryEntity;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Repository\CategoryRepositoryInterface;

class CategoryEloquentRepository implements CategoryRepositoryInterface
{
    private $model;

    public function __construct(CategoryModel $category)
    {
        $this->model = $category;
    }


    public function insert(CategoryEntity $category): CategoryEntity
    {
        $category = $this->model->create(
            [
                'id' => $category->id(),
                'name' => $category->name,
                'description' => $category->description,
                'is_active' => $category->isActive,
                'created_at' => $category->createdAt()
            ]
        );

        return $this->toCategory($category);
    }

    public function findById(string $id): CategoryEntity
    {
        if (!$category = $this->model->find($id)) {
            throw new NotFoundException();
        }

        return $this->toCategory($category);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $categories = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter)
                    $query->where('name', 'LIKE', "%{$filter}%");
            })
            ->orderBy('id', $order)
            ->get();

        return $categories->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPerPage = 15): PaginationInterface
    {
        return new PaginationPresenter();
    }

    public function update(CategoryEntity $category): CategoryEntity
    {
        return new CategoryEntity(
            id: $category->uuid,
            name: $category->name,
            description: $category->description,
            isActive: $category->is_active,
            createdAt: $category->created_at
        );
    }

    public function delete(string $id): bool
    {
        return true;
    }

    private function toCategory(object $object): CategoryEntity
    {
        return new CategoryEntity(
            id: $object->id,
            name: $object->name,
            description: $object->description,
            isActive: $object->is_active,
            createdAt: $object->created_at
        );
    }
}
