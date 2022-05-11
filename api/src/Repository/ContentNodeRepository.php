<?php

namespace App\Repository;

use App\Entity\ContentNode;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * @method null|ContentNode find($id, $lockMode = null, $lockVersion = null)
 * @method null|ContentNode findOneBy(array $criteria, array $orderBy = null)
 * @method ContentNode[]    findAll()
 * @method ContentNode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentNodeRepository extends SortableServiceEntityRepository implements CanFilterByUserInterface {
    use FiltersByContentNode;

    public function __construct(EntityManagerInterface $em) {
        parent::__construct($em, ContentNode::class);
    }

    public function filterByUser(QueryBuilder $queryBuilder, User $user): void {
        $this->filterByContentNode($queryBuilder, $user, $queryBuilder->getRootAliases()[0]);
    }
}
