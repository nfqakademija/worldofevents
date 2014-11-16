<?php

namespace Woe\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Woe\EventBundle\Entity\Distributor;

class LoadDistributorData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $distributor = $this->createDistributor('BILIETAI LT', 'http://bilietai.lt/');
        $this->addReference('event-distributor', $distributor);
        $manager->persist($distributor);
        $manager->flush();
    }

    public function getOrder()
    {
        return 30;
    }

    /**
     * @param string $name
     * @param string $url
     * @return Distributor
     */
    private function createDistributor($name, $url)
    {
        $distributor = new Distributor();
        $distributor->setName($name);
        $distributor->setUrl($url);

        return $distributor;
    }
}