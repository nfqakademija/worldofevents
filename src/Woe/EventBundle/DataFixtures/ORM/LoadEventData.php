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
        $this->loadEvent1($manager);
        $this->loadBasicEvents($manager);
        $manager->flush();
    }

    protected function loadEvent1(ObjectManager $manager)
    {
        $event = new Event();
        $event->setTitle("Duis aute irure dolor in reprehenderit");
        $event->setDate(new \DateTime('2020-12-11 17:00'));
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

        $event->addKeyword($this->getReference('event-keyword-1'));

        $manager->persist($event);
    }

    public function loadBasicEvents($manager)
    {
        $events = array(
            array(
                'title' => "LIEPSNOJANTIS KALĖDŲ LEDAS 2014",
                'date' => new \DateTime("2020-12-25 19:00"),
                'location' => $this->getReference('event-location'),
                'image' => 'http://www.bilietai.lt/event-big-photo/21512.png',
                'description' => "a\nb\nc",
                'information' => "a\nb\nc\nd"
            ),
            array(
                'title' => 'Andrius Mamontovas. Tas bičas iš "Fojė"',
                'date' => new \DateTime("2020-12-26 19:00"),
                'location' => $this->getReference('event-location'),
                'image' => 'http://www.bilietai.lt/event-big-photo/22830.png',
                'description' => null,
                'information' => null
            ),
            array(
                'title' => 'Event of the Year',
                'date' => new \DateTime("tomorrow +12 hours"),
                'location' => $this->getReference('event-location'),
                'image' => 'http://www.bilietai.lt/event-big-photo/22830.png',
                'description' => null,
                'information' => null
            )
        );

        foreach ($events as $event_data) {
            $event = $this->createMinimalEvent(
                $event_data['title'],
                $event_data['date'],
                $event_data['location'],
                $event_data['image'],
                $event_data['description'],
                $event_data['information']
            );
            $manager->persist($event);
        }

    }

    /**
     * Create event with basic required information
     *
     * @param $title
     * @param \DateTime $date
     * @param Location $location
     * @param string|null $image
     * @param string|null $description
     * @param string|null $information
     * @return Event
     */
    protected function createMinimalEvent($title, \DateTime $date, Location $location, $image = null, $description = null, $information = null)
    {
        $event = new Event();
        $event->setTitle($title);
        $event->setDate($date);
        $event->setLocation($location);
        $event->setImage($image);
        $event->setDescription($description);
        $event->setInformation($information);

        return $event;
    }

    public function getOrder()
    {
        return 50;
    }
}
