<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;

interface QueryBuilderServiceInterface
{
    public function createQueryBuilder(string $alias, ?string $indexBy = null,array $conditions = []): QueryBuilder;

}