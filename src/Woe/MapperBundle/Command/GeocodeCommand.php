<?php

namespace Woe\MapperBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GeocodeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('geocode:update')
            ->setDescription('Updates event location coordinates');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $geocoder = $this->getContainer()->get('bazinga_geocoder.geocoder');

        $locations = $doctrine->getRepository('WoeEventBundle:Location')
                ->findBy(array('latitude' => null, 'longitude' => null));

        foreach ($locations as $location) {
            $output->writeln($location->getName());
            $address = $geocoder->geocode($location->getAddress());
            $location->setLatitude($address->getLatitude());
            $location->setLongitude($address->getLongitude());
            $em->persist($location);
        }

        $em->flush();
    }
}
