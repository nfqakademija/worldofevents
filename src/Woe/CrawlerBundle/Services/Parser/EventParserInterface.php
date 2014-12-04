<?php
namespace Woe\CrawlerBundle\Services\Parser;

interface EventParserInterface
{
    /**
     * Get title
     * @return null|string
     */
    public function getTitle();

    /**
     * Get description
     * @return null|string
     */
    public function getDescription();

    /**
     * Get additional information
     * @return null|string
     */
    public function getInformation();

    /**
     * Get image URL
     * @return mixed
     */
    public function getImage();

    /**
     * Get street address
     * @return null|string
     */
    public function getAddress();

    /**
     * Get minimum price
     * @return mixed
     */
    public function getPriceMin();

    /**
     * Get maximum price
     * @return mixed
     */
    public function getPriceMax();

    /**
     * Get date
     * @return \DateTime|null
     */
    public function getDate();

    /**
     * Get city
     * @return null|string
     */
    public function getCity();

    /**
     * Get place (arena or hall name)
     * @return null|string
     */
    public function getPlace();

    /**
     * Return true if tickets are purchasable
     * @return bool
     */
    public function isOnSale();

    /**
     * Get DOM
     * @return \DOMXpath
     */
    public function getDom();

    /**
     * Set DOM
     * @param \DOMXPath $dom
     */
    public function setDom(\DOMXPath $dom);

    /**
     * Get source URL
     * @return string
     */
    public function getSourceUrl();

    /**
     * Set source URL
     * @param $source_url
     */
    public function setSourceUrl($source_url);

    /**
     * Check required fields' presence
     * @return bool
     */
    public function isValid();
}