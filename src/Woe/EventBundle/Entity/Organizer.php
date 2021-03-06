<?php

namespace Woe\EventBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Organizer
 *
 * @ORM\Table(name="organizers")
 * @ORM\Entity
 */
class Organizer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var Event[]
     *
     * @ORM\OneToMany(targetEntity="Event", mappedBy="organizer")
     */
    private $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Organizer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Organizer
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Add events
     *
     * @param \Woe\EventBundle\Entity\Event $events
     * @return Organizer
     */
    public function addEvent(\Woe\EventBundle\Entity\Event $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \Woe\EventBundle\Entity\Event $events
     */
    public function removeEvent(\Woe\EventBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return Event[]
     */
    public function getEvents()
    {
        return $this->events;
    }
}
