<?php

namespace Woe\MapperBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Woe\EventBundle\Entity\Event;

class UpdateKeywordsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mapper:keywords:update')
            ->setDescription('Updates list of keywords and mapping between keywords and events');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $events = $this->getContainer()->get('doctrine')
            ->getRepository('WoeEventBundle:Event')->findAll();

        foreach ($events as $event) {
            if ($event->getKeywords()->isEmpty()) {
                $keywords_added = $this->addNewKeywords($event);
                $output->writeln(sprintf("Added %3d keywords to %s", $keywords_added, $event->getTitle()));
            }
        }
    }

    /**
     * Add new keywords to event
     *
     * @param Event $event
     * @return int $counter number of keywords added
     */
    protected function addNewKeywords(Event $event)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $repository = $doctrine->getRepository('WoeEventBundle:Keyword');
        $em = $doctrine->getManager();

        $text_normalizer = $this->getContainer()->get('woe_mapper.text_normalizer');
        $keywords = $text_normalizer->normalize(
            $event->getTitle(). " " . $event->getDescription()
        );

        $counter = 0;

        foreach ($keywords as $keyword_name) {
            $keyword = $repository->findOrCreate($keyword_name);
            if (!$event->getKeywords()->contains($keyword)) {
                $event->addKeyword($keyword);
                $em->persist($keyword);
                $em->persist($event);
                $counter++;
            }
        }

        $em->flush();

        return $counter;
    }
}
