<?php

namespace Woe\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Woe\EventBundle\Entity\Location;
use Woe\EventBundle\Entity\City;

class LoadLocationData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $city = $this->createCity("Vilnius");
        $location = $this->createLocation(
            "NFQ Akademija",
            "Konstitucijos pr. 7",
            54.696467,
            25.276848,
            $city
        );

        $this->addReference('event-location', $location);

        $manager->persist($city);
        $manager->persist($location);
        $manager->flush();
    }

    /**
     * @param string $name
     * @return City
     */
    private function createCity($name)
    {
        $city = new City();
        $city->setName($name);

        return $city;
    }

    /**
     * @param string $name
     * @param string $address
     * @param float $latitude
     * @param float $longitude
     * @param City $city
     * @return Location
     */
    private function createLocation($name, $address, $latitude, $longitude, City $city)
    {
        $location = new Location();
        $location->setName($name);
        $location->setAddress($address);
        $location->setLatitude($latitude);
        $location->setLongitude($longitude);
        $location->setCity($city);

        return $location;
    }

    public function getOrder()
    {
        return 40;
    }
}