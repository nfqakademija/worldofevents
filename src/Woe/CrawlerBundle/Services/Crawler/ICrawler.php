<?php
namespace Woe\CrawlerBundle\Services\Crawler;

use Woe\CrawlerBundle\Services\Parser\EventParser;

interface ICrawler
{
    /**
     * Get URL of the current page
     * @return string
     */
    public function getCurrentPageUrl();

    /**
     * @return string
     */
    public function getSiteRootUrl();

    /**
     * @return string
     */
    public function getXpathNextPageButton();

    /**
     * @return string
     */
    public function getXpathEventNodes();

    /**
     * @return string
     */
    public function getXpathEventPrice();

    /**
     * @return string
     */
    public function getXpathEventUrl();

    public function __construct(EventParser $parser);

    /**
     * Download HTML of a page and build DOM
     *
     * @param $url
     * @return \DOMXPath
     */
    public function fetchUrl($url);

    /**
     * Increment current page index
     */
    public function nextPage();

    /**
     * Download HTML of the current page and build DOM
     */
    public function fetchCurrentPage();

    /**
     * Get DOM of the current page
     * @return \DOMXPath
     */
    public function getCurrentPage();

    /**
     * Check whether the current page has 'Next' button
     *
     * @return bool
     */
    public function hasNextPage();

    /**
     * Get EventParser object
     * @param \DOMXpath $dom
     * @param $source_url
     * @return EventParser
     */
    public function getEventParser(\DOMXpath $dom, $source_url);

    /**
     * Get list of event objects
     *
     * @return EventParser[]
     */
    public function getEvents();

    /**
     * Returns true if event tickets are still purchasable
     * @param \DOMNode $event_node
     * @return bool
     */
    public function isOnSale($event_node);

    /**
     * Extract URL of event from DOM node
     * @param \DOMNode $event_node
     * @return string
     */
    public function getEventUrl($event_node);
}