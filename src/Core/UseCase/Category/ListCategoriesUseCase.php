<?php

namespace Core\UseCase\Category;

use Core\UseCase\DTO\Category\ListCategoriesInputDto;
use Core\UseCase\DTO\Category\ListCategoriesOutputDto;
use Core\Domain\Repository\CategoryRepositoryInterface;

class ListCategoriesUseCase
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ListCategoriesInputDto $input): ListCategoriesOutputDto
    {
        $categories = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPerPage: $input->totalPerPage
        );

        return new ListCategoriesOutputDto(
            items: $categories->items(),
            total: $categories->total(),
            last_page: $categories->lastPage(),
            first_page: $categories->firstPage(),
            current_page: $categories->currentPage(),
            per_page: $categories->perPage(),
            to: $categories->to(),
            from: $categories->from()
        );
    }
}
