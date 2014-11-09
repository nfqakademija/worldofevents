<?php

namespace Woe\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Woe\EventBundle\Entity\Distributor;
use Woe\EventBundle\Entity\Event;
use Woe\EventBundle\Entity\Location;
use Woe\EventBundle\Entity\Organizer;
use Woe\EventBundle\Entity\Tag;

class LoadEventData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $event = $this->createEvent(
            "Duis aute irure dolor in reprehenderit",
            new \DateTime('now'),
            "10.00",
            "20.00",
            "Lorem ipsum dolor sit amet, consectetur adipisicing elit",
            "Ut enim ad minim veniam, quis nostrud exercitation ullamco",
            "http://fake.dev/event/15",
            "image.jpg",
            $this->getReference('event-location'),
            $this->getReference('event-organizer'),
            $this->getReference('event-distributor'),
            array(
                $this->getReference('event-tag-1'),
                $this->getReference('event-tag-2'),
                $this->getReference('event-tag-3'),
            )
        );

        $manager->persist($event);
        $manager->flush();
    }

    /**
     * @param string $title
     * @param \Datetime $date
     * @param string $price_min
     * @param string $price_max
     * @param string $description
     * @param string $information
     * @param string $url
     * @param string $image
     * @param Location $location
     * @param Organizer $organizer
     * @param Distributor $distributor
     * @param Tag[] $tags
     * @return Event
     */
    private function createEvent(
        $title,
        \Datetime $date,
        $price_min,
        $price_max,
        $description,
        $information,
        $url,
        $image,
        Location $location,
        Organizer $organizer,
        Distributor $distributor,
        array $tags
    ) {
        $event = new Event();

        $event->setTitle($title);
        $event->setDate($date);
        $event->setPriceMin($price_min);
        $event->setPriceMax($price_max);
        $event->setDescription($description);
        $event->setInformation($information);
        $event->setSourceUrl($url);
        $event->setImage($image);
        $event->setLocation($location);
        $event->setOrganizer($organizer);
        $event->setDistributor($distributor);

        foreach ($tags as $tag) {
            $event->addTag($tag);
        }

        return $event;
    }

    public function getOrder()
    {
        return 50;
    }
}
