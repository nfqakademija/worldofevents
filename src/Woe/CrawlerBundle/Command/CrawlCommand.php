<?php
namespace Woe\CrawlerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Woe\CrawlerBundle\BilietaiCrawler;

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
        $crawler = new BilietaiCrawler();

        do {
            $crawler->fetchCurrentPage();
            $events = $crawler->getEvents();

            foreach ($events as $event) {
                $output->writeln($event->getSourceUrl());
                $output->writeln($event->getTitle());
                $output->writeln($event->getPriceRange());
                // save to DB
                // ...
            }

            $crawler->nextPage();
        } while ($crawler->hasNextPage());

        $output->writeln("It works!");
    }
}
