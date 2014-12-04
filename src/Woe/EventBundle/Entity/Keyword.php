<?php

namespace Woe\EventBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Keyword
 *
 * @ORM\Table(name="keywords")
 * @ORM\Entity
 */
class Keyword
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
     * @var Tag[]
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="keywords")
     * @ORM\JoinTable(name="keywords_tags")
     */
    private $tags;

    /**
     * @var Event[]
     *
     * @ORM\ManyToMany(targetEntity="Event", mappedBy="keywords")
     */
    private $events;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * @return Keyword
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
     * Add tags
     *
     * @param Tag $tags
     * @return Keyword
     */
    public function addTag(Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param Tag $tags
     */
    public function removeTag(Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return Tag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add event
     *
     * @param Event $event
     * @return Keyword
     */
    public function addEvent(Event $event)
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param Event $event
     */
    public function removeEvent(Event $event)
    {
        $this->events->removeElement($event);
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
