<?php

namespace Core\UseCase\DTO\Category\List;

class ListCategoriesInputDto
{
    public function __construct(
        public string $filter = '',
        public string $order = 'DESC',
        public int $page = 1,
        public int $totalPerPage = 15
    ) {
    }
}
