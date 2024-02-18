<?php

namespace Core\UseCase\Category;

use Core\UseCase\DTO\Category\CategoryInputDto;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\Delete\CategoryDeleteOutputDto;

class DeleteCategoryUseCase
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CategoryInputDto $input): CategoryDeleteOutputDto
    {
        $categoryDeleted = $this->repository->delete($input->id);

        return new CategoryDeleteOutputDto(
            success: $categoryDeleted
        );
    }
}
