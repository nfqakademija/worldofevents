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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="source_url", type="string", length=255)
     */
    private $source_url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="price_min", type="decimal", scale=2)
     */
    private $priceMin;

    /**
     * @var string
     *
     * @ORM\Column(name="price_max", type="decimal", scale=2)
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
     * @ORM\ManyToOne(targetEntity="Distributor", inversedBy="events")
     * @ORM\JoinColumn(name="distributor_id", referencedColumnName="id")
     */
    private $distributor;

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

    /**
     * Set title
     *
     * @param string $title
     * @return Event
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Event
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set distributor
     *
     * @param \Woe\EventBundle\Entity\Distributor $distributor
     * @return Event
     */
    public function setDistributor(\Woe\EventBundle\Entity\Distributor $distributor = null)
    {
        $this->distributor = $distributor;

        return $this;
    }

    /**
     * Get distributor
     *
     * @return \Woe\EventBundle\Entity\Distributor 
     */
    public function getDistributor()
    {
        return $this->distributor;
    }

    /**
     * Set source_url
     *
     * @param string $sourceUrl
     * @return Event
     */
    public function setSourceUrl($sourceUrl)
    {
        $this->source_url = $sourceUrl;

        return $this;
    }

    /**
     * Get source_url
     *
     * @return string 
     */
    public function getSourceUrl()
    {
        return $this->source_url;
    }
}
