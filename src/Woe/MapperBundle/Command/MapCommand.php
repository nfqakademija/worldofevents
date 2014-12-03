<?php

namespace Woe\MapperBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MapCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tags:update')
            ->setDescription('Updates event tags based on keyword -> tag relations');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $text_normalizer = $this->getContainer()->get('woe_mapper.text_normalizer');

        $event_repository = $doctrine->getRepository('WoeEventBundle:Event');
        $events = $event_repository->findAll();

        foreach ($events as $event) {
            $text = $event->getTitle();
            $keywords = $doctrine->getRepository('WoeEventBundle:Keyword')
                ->findBy(array('name' => $text_normalizer->normalize($text)));

            foreach ($keywords as $keyword) {
                $tags = $keyword->getTags();
                // TODO: don't add duplicate tags
                $event->addTags($tags);
                $em->persist($event);
            }
        }

        $em->flush();
    }
}
