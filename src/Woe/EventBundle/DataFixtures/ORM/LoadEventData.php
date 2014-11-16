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
        $event->setTitle("Duis aute irure dolor in reprehenderit");
        $event->setDate(new \DateTime('now'));
        $event->setLocation($this->getReference('event-location'));
        $event->setPriceMin("10.00");
        $event->setPriceMax("20.00");
        $event->setDescription("Lorem ipsum dolor sit amet, consectetur adipisicing elit");
        $event->setInformation("Ut enim ad minim veniam, quis nostrud exercitation ullamco");
        $event->setSourceUrl("http://fake.dev/event/15");
        $event->setImage("image.jpg");
        $event->setOrganizer($this->getReference('event-organizer'));
        $event->setDistributor($this->getReference('event-distributor'));

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
