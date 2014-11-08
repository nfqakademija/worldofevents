<?php

namespace Woe\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Woe\EventBundle\Entity\Organizer;

class LoadOrganizerData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $organizer = $this->createOrganizer('NFQ', 'http://nfq.lt/');
        $this->addReference('event-organizer', $organizer);
        $manager->persist($organizer);
        $manager->flush();
    }

    public function getOrder()
    {
        return 30;
    }

    /**
     * @param string $name
     * @param string $url
     * @return Organizer
     */
    private function createOrganizer($name, $url)
    {
        $organizer = new Organizer();
        $organizer->setName($name);
        $organizer->setUrl($url);

        return $organizer;
    }
}