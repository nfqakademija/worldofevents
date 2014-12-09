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
            try {
                $address = $geocoder->geocode($location->getAddress());
            } catch (\Geocoder\Exception\NoResultException $e) {
                $output->writeln("Can't find coordinates of: " . $location->getAddress());
                continue;
            } catch (\Geocoder\Exception\QuotaExceededException $e) {
                $output->writeln("Google wants your money (quota exceeded)");
                continue;
            }

            $output->writeln(sprintf(
                'Coordinates of %s: %s, %s',
                $location->getName(),
                $address->getLatitude(),
                $address->getLongitude()
            ));

            $location->setLatitude($address->getLatitude());
            $location->setLongitude($address->getLongitude());
            $em->persist($location);
        }

        $em->flush();
    }
}
