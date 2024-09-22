<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

class UserQueryBuilderService implements QueryBuilderServiceInterface
{

    public function __construct(private UserRepository $userRepository, private Security $security)
    {
    }

    public function createQueryBuilder(string $alias, ?string $indexBy = null,array $conditions = []): QueryBuilder
    {
        $queryBuilder = $this->userRepository->createQueryBuilder($alias);
        if (!$this->security->isGranted("ROLE_ADMIN")) {
            $queryBuilder->andWhere("$alias.visibility = :visibility")
                ->setParameter('visibility', 'public');
        }

        foreach ($conditions as $condition) {
            $queryBuilder->andWhere($condition['expr'])
                ->setParameter($condition['param'], $condition['value']);
        }

        return $queryBuilder;
    }
}

