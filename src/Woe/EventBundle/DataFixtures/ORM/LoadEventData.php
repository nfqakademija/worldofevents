<?php

namespace Woe\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Woe\EventBundle\Entity\Event;

class LoadEventData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $event = new Event();
        $event->setRequiredFields(
            "Duis aute irure dolor in reprehenderit",
            new \DateTime('now'),
            $this->getReference('event-location')
        );

        $event->setOptionalFields(
            "10.00",
            "20.00",
            "Lorem ipsum dolor sit amet, consectetur adipisicing elit",
            "Ut enim ad minim veniam, quis nostrud exercitation ullamco",
            "http://fake.dev/event/15",
            "image.jpg",
            $this->getReference('event-organizer'),
            $this->getReference('event-distributor')
        );

        $event->addTags(
            array(
                $this->getReference('event-tag-1'),
                $this->getReference('event-tag-2'),
                $this->getReference('event-tag-3'),
            )
        );

        $manager->persist($event);
        $manager->flush();
    }

    public function getOrder()
    {
        return 50;
    }
}
