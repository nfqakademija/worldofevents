<?php

namespace Woe\EventBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends EntityRepository
{
    public function findByKeywords(array $keywords)
    {
        $query = $this->createQueryBuilder('event')
            ->innerJoin('event.keywords', 'k')
            ->where('k.name IN (:keywords)')
            ->setParameter('keywords', $keywords);

        return $query->getQuery()->getResult();
    }
}
