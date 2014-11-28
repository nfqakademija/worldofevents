<?php
namespace Woe\CrawlerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Woe\CrawlerBundle\Services\Parser\EventParser;
use Woe\EventBundle\Entity\Event;

class CrawlCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('crawler:run')
            ->setDescription('Searches for new events and saves data to database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $crawler = $this->getContainer()->get('woe_crawler.bilietai_crawler');

        do {
            $crawler->fetchCurrentPage();
            $events = $crawler->getEvents();

            foreach ($events as $event) {
                if ($event->isValid()) {
                    $output->writeln($event->getSourceUrl());
                    $output->writeln($event->getTitle());
                    $this->saveEvent($event);
                }
            }

            $crawler->nextPage();
        } while ($crawler->hasNextPage());

        $output->writeln("It works!");
    }

    protected function saveEvent(EventParser $parser)
    {
        $event = new Event();

        $event->setTitle($parser->getTitle());
        $event->setDescription($parser->getDescription());
        $event->setInformation($parser->getInformation());
        $event->setPriceMin($parser->getPriceMin());
        $event->setPriceMax($parser->getPriceMax());
        $event->setImage($parser->getImage());
        $event->setSourceUrl($parser->getSourceUrl());
        $event->setDate($parser->getDate());

        $doctrine = $this->getContainer()->get('doctrine');
        $event_city = $doctrine->getRepository('WoeEventBundle:City')
            ->findOrCreateCity($parser->getCity());

        $event_location = $doctrine->getRepository('WoeEventBundle:Location')
            ->findOrCreateLocation(
                $parser->getPlace(),
                $parser->getAddress(),
                $event_city
            );

        $event->setLocation($event_location);

        $em = $doctrine->getManager();
        $em->persist($event);
        $em->persist($event_city);
        $em->persist($event_location);
        $em->flush();
    }
}
