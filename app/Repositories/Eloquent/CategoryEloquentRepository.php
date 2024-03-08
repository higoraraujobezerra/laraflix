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
        $query = $this->model;
        if ($filter)
            $query->where('name', 'LIKE', "%{$filter}%");
        $query->orderBy('id', $order);
        $paginator = $query->paginate($totalPerPage);

        return new PaginationPresenter($paginator);
    }

    public function update(CategoryEntity $category): CategoryEntity
    {
        if (!$categoryDb = $this->model->find($category->id())) {
            throw new NotFoundException("Category not found!");
        }

        $categoryDb->update([
            'name'          => $category->name,
            'description'   => $category->description,
            'is_active'     => $category->isActive
        ]);
        $categoryDb->refresh();

        return $this->toCategory($categoryDb);
    }

    public function delete(string $id): bool
    {
        if (!$categoryDb = $this->model->find($id)) {
            throw new NotFoundException("Category not found!");
        }

        return $categoryDb->delete();
    }

    private function toCategory(object $object): CategoryEntity
    {
        $entity =  new CategoryEntity(
            id: $object->id,
            name: $object->name,
            description: $object->description
        );

        ((bool) $object->is_active ? $entity->activate() : $entity->disable());

        return $entity;
    }
}
