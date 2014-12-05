<?php

namespace Woe\MapperBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateTagsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mapper:tags:update')
            ->setDescription('Updates event tags based on keyword -> tag relations');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $events = $doctrine->getRepository('WoeEventBundle:Event')->findAll();

        foreach ($events as $event) {
            foreach ($event->getKeywords() as $keyword) {
                foreach ($keyword->getTags() as $tag) {
                    if (!$event->getTags()->contains($tag)) {
                        $output->writeln("Adding tag: " . $tag->getName());
                        $event->addTag($tag);
                    }
                }
            }
            $em->persist($event);
        }

        $em->flush();
    }
}
