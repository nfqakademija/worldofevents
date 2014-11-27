<?php
namespace Woe\CrawlerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
                $output->writeln($event->getSourceUrl());
                $output->writeln($event->getTitle());
            }

            $crawler->nextPage();
        } while ($crawler->hasNextPage());

        $output->writeln("It works!");
    }
}
