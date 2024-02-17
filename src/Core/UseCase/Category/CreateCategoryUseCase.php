<?php

namespace Core\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\UseCase\DTO\Category\CategoryCreateInputDto;
use Core\UseCase\DTO\Category\CategoryCreateOutputDto;
use Core\Domain\Repository\CategoryRepositoryInterface;

class CreateCategoryUseCase
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CategoryCreateInputDto $input): CategoryCreateOutputDto
    {
        $category = new Category(
            name: $input->name,
            description: $input->description,
            isActive: $input->isActive
        );

        $newCategory = $this->repository->insert($category);

        return new CategoryCreateOutputDto(
            id: $newCategory->id(),
            name: $newCategory->name,
            description: $newCategory->description,
            isActive: $newCategory->isActive
        );
    }
}
