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
                $status = $this->handleEvent($event);
                $output->writeln($status);
            }

            $crawler->nextPage();
        } while ($crawler->hasNextPage());

        $output->writeln("Done!");
    }

    /**
     * Save event to database
     *
     * @param EventParser $parser
     */
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

    /**
     * Process and return status message about the event
     *
     * @param $event
     * @return string
     */
    protected function handleEvent($event)
    {
        $stored_event = $this->getContainer()->get('doctrine')->getRepository('WoeEventBundle:Event')
            ->findOneBy(array('source_url' => $event->getSourceUrl()));

        if (!is_null($stored_event)) {
            $output_color = "green";
            $output_status = "[SKIPPED]";
        } elseif ($event->isValid()) {
            $output_color = "cyan";
            $output_status = "[ADDED]";
            $this->saveEvent($event);
        } else {
            $output_color = "red";
            $output_status = "[ERROR]";
        }

        $output_format = '<fg=%s>%9s - %s - %s</fg=%1$s>';
        $status = sprintf($output_format, $output_color, $output_status, $event->getSourceUrl(), $event->getTitle());

        return $status;
    }
}
