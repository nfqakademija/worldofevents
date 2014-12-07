<?php

namespace Woe\EventBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * NotificationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NotificationRepository extends EntityRepository
{
    public function findAllForSending()
    {
        $query = $this->createQueryBuilder('n')
            ->where('n.date < :now')
            ->setParameter('now', new \DateTime('now'))
            ->getQuery();
        return $query->getResult();
    }
}
