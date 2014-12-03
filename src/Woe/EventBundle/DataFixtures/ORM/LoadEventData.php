<?php

namespace Woe\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Woe\EventBundle\Entity\Event;
use Woe\EventBundle\Entity\Location;

class LoadEventData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $event = new Event();
        $event->setTitle("Duis aute irure dolor in reprehenderit");
        $event->setDate(new \DateTime('2014-12-11 17:00'));
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

        $event2 = $this->createMinimalEvent(
            "LIEPSNOJANTIS KALĖDŲ LEDAS 2014",
            new \DateTime("2014-12-25 19:00"),
            $this->getReference('event-location')
        );

        $event2->setImage('http://www.bilietai.lt/event-big-photo/21512.png');
        $event2->setDescription("a\nb\nc");
        $event2->setInformation("a\nb\nc\nd");

        $event3 = $this->createMinimalEvent(
            'Andrius Mamontovas. Tas bičas iš "Fojė"',
            new \DateTime("2014-12-26 19:00"),
            $this->getReference('event-location')
        );

        $event3->setImage('http://www.bilietai.lt/event-big-photo/22830.png');

        $manager->persist($event);
        $manager->persist($event2);
        $manager->persist($event3);
        $manager->flush();
    }

    /**
     * Create event with basic required information
     *
     * @param $title
     * @param \DateTime $date
     * @param Location $location
     * @return Event
     */
    protected function createMinimalEvent($title, \DateTime $date, Location $location)
    {
        $event = new Event();
        $event->setTitle($title);
        $event->setDate($date);
        $event->setLocation($location);

        return $event;
    }

    public function getOrder()
    {
        return 50;
    }
}
