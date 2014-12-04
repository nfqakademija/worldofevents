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

        $text_normalizer = $this->getContainer()->get('woe_mapper.text_normalizer');
        $events = $doctrine->getRepository('WoeEventBundle:Event')->findAll();

        foreach ($events as $event) {
            $text = $event->getTitle();
            $keywords = $doctrine->getRepository('WoeEventBundle:Keyword')
                ->findBy(array('name' => $text_normalizer->normalize($text)));

            foreach ($keywords as $keyword) {
                $tags = $this->getNewEventTags($event, $keyword);
                $event->addTags($tags);
                $em->persist($event);
            }
        }

        $em->flush();
    }

    /**
     * Get new event tags
     *
     * @param $event
     * @param $keyword
     * @return mixed
     */
    protected function getNewEventTags($event, $keyword)
    {
        $event_tags = $event->getTags()->toArray();
        $tags = $keyword->getTags()->filter(
            function ($tag) use ($event_tags) {
                if (!in_array($tag, $event_tags)) {
                    return true;
                }
            }
        );
        return $tags;
    }
}
