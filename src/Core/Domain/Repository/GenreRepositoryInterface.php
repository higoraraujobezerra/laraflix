<?php

namespace Core\Domain\Repository;

use Core\Domain\Entity\Genre;
use Core\Domain\Repository\PaginationInterface;

interface GenreRepositoryInterface
{
    public function insert(Genre $genre): Genre;
    public function findById(string $id): Genre;
    public function findAll(string $filter = '', $order = 'DESC'): array;
    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPerPage = 15): PaginationInterface;
    public function update(Genre $genre): Genre;
    public function delete(string $id): bool;
}