<?php

namespace Woe\FilterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Tag
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="KeywordNorm", mappedBy="tags")
     */
    private $keywords_norm;

    /**
     * @ORM\ManyToMany(targetEntity="Woe\EventBundle\Entity\Event", mappedBy="tags")
     */
    private $events;

    public function __construct()
    {
        $this->keywords_norm = new ArrayCollection();
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
     * @return Tag
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
     * Add keywords_norm
     *
     * @param \Woe\FilterBundle\Entity\KeywordNorm $keywordsNorm
     * @return Tag
     */
    public function addKeywordsNorm(\Woe\FilterBundle\Entity\KeywordNorm $keywordsNorm)
    {
        $this->keywords_norm[] = $keywordsNorm;

        return $this;
    }

    /**
     * Remove keywords_norm
     *
     * @param \Woe\FilterBundle\Entity\KeywordNorm $keywordsNorm
     */
    public function removeKeywordsNorm(\Woe\FilterBundle\Entity\KeywordNorm $keywordsNorm)
    {
        $this->keywords_norm->removeElement($keywordsNorm);
    }

    /**
     * Get keywords_norm
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getKeywordsNorm()
    {
        return $this->keywords_norm;
    }

    /**
     * Add events
     *
     * @param \Woe\EventBundle\Entity\Event $events
     * @return Tag
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
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }
}
