<?php

namespace Woe\EventBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Woe\EventBundle\Entity\Event;

class DatetimeToDaysBeforeEventTransformer implements DataTransformerInterface
{
    /**
     * @var Event
     */
    private $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Transforms notification date to number of days before event
     *
     * @param mixed $notificationDate
     * @return int
     */
    public function transform($notificationDate)
    {
        if (!$notificationDate) {
            return 0;
        }

        $eventDate = $this->event->getDate();
        $interval = $notificationDate->diff($eventDate);
        return $interval->format('%d');
    }

    /**
     * Transform number of days before event to notification date
     *
     * @param mixed $daysBefore
     * @return \DateTime|null
     */
    public function reverseTransform($daysBefore)
    {
        if (!$daysBefore) {
            return null;
        }

        $interval = new \DateInterval('P' . (int) $daysBefore . 'D');
        return $this->event->getDate()->sub($interval);
    }
}
