<?php

namespace Woe\NotificationBundle\Services;

use Woe\EventBundle\Entity\Notification;

class NotificationService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(Notification $notification)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("Priminimas apie renginį " . $notification->getEvent()->getTitle())
            ->setFrom("info@worldofevents.dev")
            ->setTo($notification->getEmail())
            ->setBody("Daugiau informacijos: " . $notification->getEvent()->getSourceUrl());

        $this->mailer->send($message);
    }
}
