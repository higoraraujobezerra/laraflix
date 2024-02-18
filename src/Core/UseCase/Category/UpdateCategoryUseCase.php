<?php

namespace Core\UseCase\Category;

use Core\UseCase\DTO\Category\CategoryUpdateInputDto;
use Core\UseCase\DTO\Category\CategoryUpdateOutputDto;
use Core\Domain\Repository\CategoryRepositoryInterface;

class UpdateCategoryUseCase
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CategoryUpdateInputDto $input): CategoryUpdateOutputDto
    {
        $category = $this->repository->findById($input->id);

        $category->update(
            name: $input->name,
            description: $input->description ?? $category->description
        );

        $categoruUpdated = $this->repository->update($category);

        return new CategoryUpdateOutputDto(
            id: $categoruUpdated->id,
            name: $categoruUpdated->name,
            description: $categoruUpdated->description,
            is_active: $categoruUpdated->isActive
        );
    }
}
