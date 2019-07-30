<?php
declare(strict_types=1);

namespace php_part\repositories;

use Illuminate\Database\Eloquent\Collection;

interface ArticleRepositoryInterface
{
    /**
     * @param array $attributes
     * @return int
     */
    public function store(array $attributes) : int;

    /**
     * @return Collection
     */
    public function findAll() : Collection;
}
