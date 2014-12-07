<?php

namespace Woe\NotificationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendNotificationsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('notifications:send')
            ->setDescription('Sends emails to users notifying about upcoming event');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();
        $repository =  $doctrine->getRepository('WoeEventBundle:Notification');

        /** @var \Woe\EventBundle\Entity\Notification $notification */
        foreach ($repository->findAllForSending() as $notification) {
            $output->writeln("Sending notification to: " . $notification->getEmail());
            $this->getContainer()->get('woe_notification.notification_service')
                ->send($notification);
            $em->remove($notification);
        }

        $em->flush();
    }
}
