<?php

namespace Woe\CrawlerBundle\Services\Crawler;

use Woe\CrawlerBundle\Services\Parser\EventParser;

abstract class Crawler implements ICrawler
{
    protected $page_index = 1;
    protected $parser;
    /* @var \DOMXPath $current_page */
    protected $current_page;

    public function __construct(EventParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Download HTML of a page and build DOM
     *
     * @param $url
     * @return \DOMXPath
     */
    public function fetchUrl($url)
    {
        $html = file_get_contents($url);

        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $domxpath = new \DOMXPath($dom);

        return $domxpath;
    }

    /**
     * Increment current page index
     */
    public function nextPage()
    {
        $this->page_index += 1;
    }

    /**
     * Download HTML of the current page and build DOM
     */
    public function fetchCurrentPage()
    {
        $url = $this->getCurrentPageUrl();
        $this->current_page = $this->fetchUrl($url);
    }

    /**
     * Get DOM of the current page
     * @return \DOMXPath
     */
    public function getCurrentPage()
    {
        return $this->current_page;
    }

    /**
     * Check whether the current page has 'Next' button
     *
     * @return bool
     */
    public function hasNextPage()
    {
        $xpath = $this->getXpathNextPageButton();
        return $this->getCurrentPage() && $this->getCurrentPage()->query($xpath)->length !== 0;
    }

    /**
     * Get EventParser object
     * @param \DOMXpath $dom
     * @param $source_url
     * @return EventParser
     */
    public function getEventParser(\DOMXpath $dom, $source_url)
    {
        $this->parser->setDom($dom);
        $this->parser->setSourceUrl($source_url);
        return $this->parser;
    }

    /**
     * Get list of event objects
     *
     * @return EventParser[]
     */
    public function getEvents()
    {
        $events = [];
        $xpath = $this->getXpathEventNodes();

        /* @var \DOMElement $node */
        foreach ($this->getCurrentPage()->query($xpath) as $event_node) {
            if ($this->isOnSale($event_node)) {
                $event_url = $this->getEventUrl($event_node);
                $event_dom = $this->fetchUrl($event_url);

                $events[] = clone $this->getEventParser($event_dom, $event_url);
            }
        }
        return $events;
    }

    /**
     * Returns true if event tickets are still purchasable
     * @param \DOMNode $event_node
     * @return bool
     */
    public function isOnSale($event_node)
    {
        $xpath = $this->getXpathEventPrice();
        $price_node = $this->getCurrentPage()->query($xpath, $event_node);
        $price = $price_node->length !== 0 ? $price_node->item(0)->nodeValue : null;
        return $price && preg_match('/\d{1,2}\.\d{1,2}/', $price);
    }

    /**
     * Extract URL of event from DOM node
     * @param \DOMNode $event_node
     * @return string
     */
    public function getEventUrl($event_node)
    {
        $xpath = $this->getXpathEventUrl();
        $url_node = $this->getCurrentPage()->query($xpath, $event_node);
        $url = $url_node->length !== 0 ? $url_node->item(0)->getAttribute("href") : null;
        return $this->getSiteRootUrl() . $url;
    }
}
