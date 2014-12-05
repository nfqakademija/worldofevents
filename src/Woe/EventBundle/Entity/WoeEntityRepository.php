<?php

namespace Woe\EventBundle\Entity;

use Doctrine\ORM\EntityRepository;

class WoeEntityRepository extends EntityRepository
{
    /**
     * Find entity by name or create new if it does not exist
     *
     * @param string $name
     * @return object the entity instance
     */
    public function findOrCreate($name)
    {
        $entity_object = $this->findOneBy(array(
            'name'      => $name,
        ));

        if (is_null($entity_object)) {
            $entity = $this->getEntityName();
            $entity_object = new $entity();
            $entity_object->setName($name);
        }

        return $entity_object;
    }
}
