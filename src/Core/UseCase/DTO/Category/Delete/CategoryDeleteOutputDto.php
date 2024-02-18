<?php

namespace Core\UseCase\DTO\Category\Delete;

class CategoryDeleteOutputDto
{
    public function __construct(
        public bool $success
    ) {
    }
}
