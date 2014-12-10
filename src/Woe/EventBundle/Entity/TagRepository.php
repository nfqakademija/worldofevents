<?php

namespace Woe\EventBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TagRepository extends EntityRepository
{
    /**
     * Returns list of tags with their total count.
     *
     * @param int $limit
     * @return array
     */
    public function findRandomWithCount($limit = 20)
    {
        $query = $this->createQueryBuilder('tag')
            ->select('tag AS eventTag, COUNT(e.id) AS eventCount, RAND() AS HIDDEN rand')
            ->innerJoin('tag.events', 'e')
            ->groupBy('tag.id')
//            ->orderBy('rand')
            ->setMaxResults($limit);

        return $query->getQuery()->getResult();
    }
}
