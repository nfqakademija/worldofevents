<?php

namespace Woe\EventBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Woe\EventBundle\Entity\EventRepository")
 */
class Event
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="price_min", type="decimal")
     */
    private $priceMin;

    /**
     * @var string
     *
     * @ORM\Column(name="price_max", type="decimal")
     */
    private $priceMax;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="information", type="text")
     */
    private $information;

    /**
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="events")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity="Organizer", inversedBy="events")
     * @ORM\JoinColumn(name="organizer_id", referencedColumnName="id")
     */
    private $organizer;

    /**
     * @ORM\ManyToMany(targetEntity="Woe\FilterBundle\Entity\Tag", inversedBy="events")
     * @ORM\JoinTable(name="events_tags")
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * Set date
     *
     * @param \DateTime $date
     * @return Event
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set priceMin
     *
     * @param string $priceMin
     * @return Event
     */
    public function setPriceMin($priceMin)
    {
        $this->priceMin = $priceMin;

        return $this;
    }

    /**
     * Get priceMin
     *
     * @return string 
     */
    public function getPriceMin()
    {
        return $this->priceMin;
    }

    /**
     * Set priceMax
     *
     * @param string $priceMax
     * @return Event
     */
    public function setPriceMax($priceMax)
    {
        $this->priceMax = $priceMax;

        return $this;
    }

    /**
     * Get priceMax
     *
     * @return string 
     */
    public function getPriceMax()
    {
        return $this->priceMax;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set information
     *
     * @param string $information
     * @return Event
     */
    public function setInformation($information)
    {
        $this->information = $information;

        return $this;
    }

    /**
     * Get information
     *
     * @return string 
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * Set location
     *
     * @param \Woe\EventBundle\Entity\Location $location
     * @return Event
     */
    public function setLocation(\Woe\EventBundle\Entity\Location $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \Woe\EventBundle\Entity\Location 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set organizer
     *
     * @param \Woe\EventBundle\Entity\Organizer $organizer
     * @return Event
     */
    public function setOrganizer(\Woe\EventBundle\Entity\Organizer $organizer = null)
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * Get organizer
     *
     * @return \Woe\EventBundle\Entity\Organizer 
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }

    /**
     * Add tags
     *
     * @param \Woe\FilterBundle\Entity\Tag $tags
     * @return Event
     */
    public function addTag(\Woe\FilterBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \Woe\FilterBundle\Entity\Tag $tags
     */
    public function removeTag(\Woe\FilterBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }
}
