<?php

namespace Woe\CrawlerBundle\Services\Crawler;

use Woe\CrawlerBundle\Services\Parser\BilietaiEventParser;

class BilietaiCrawler
{
    const BILIETAI_ROOT_URL = "http://www.bilietai.lt/";

    private $parser;
    private $page_index = 1;
    /* @var \DOMXPath $current_page */
    private $current_page;

    public function __construct(BilietaiEventParser $parser)
    {
        $this->parser = $parser;
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
     * Get list of event objects
     *
     * @return BilietaiEventParser[]
     */
    public function getEvents()
    {
        $events = [];
        $xpath = "//td[contains(concat(' ', @class, ' '), ' list_item ')]";

        /* @var \DOMElement $node */
        foreach ($this->getCurrentPage()->query($xpath) as $event_node) {
            if ($this->isOnSale($event_node)) {
                $event_url = $this->getEventUrl($event_node);
                $event_dom = $this->fetchUrl($event_url);

                $events[] = clone $this->getBilietaiEventParser($event_dom, $event_url);
            }
        }
        return $events;
    }

    /**
     * Check whether the current page has 'Next' button
     *
     * @return bool
     */
    public function hasNextPage()
    {
        $xpath = "//*[contains(concat(' ', @class, ' '), ' next ')]";
        return $this->getCurrentPage() && $this->getCurrentPage()->query($xpath)->length !== 0;
    }

    /**
     * Increment current page index
     */
    public function nextPage()
    {
        $this->page_index += 1;
    }

    /**
     * Get URL of the current page
     * @return string
     */
    public function getCurrentPageUrl()
    {
        return self::BILIETAI_ROOT_URL . "lt/category/0/" . $this->page_index;
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
     * Returns true if event tickets are still purchasable
     * @param \DOMNode $event_node
     * @return bool
     */
    public function isOnSale($event_node)
    {
        $xpath = "div[contains(@class, 'wf')]//div[contains(@class, 'price_padd')]/strong";
        $price_node = $this->getCurrentPage()->query($xpath, $event_node);
        $price = $price_node->length !== 0 ? $price_node->item(0)->nodeValue : null;
        return $price && strpos($price, 'parduota') === false;
    }

    /**
     * Extract URL of event from DOM node
     * @param \DOMNode $event_node
     * @return string
     */
    public function getEventUrl($event_node)
    {
        $xpath = "div[contains(@class, 'list_item_cont')]//a[starts-with(@href, 'lt/event/')]";
        $url_node = $this->getCurrentPage()->query($xpath, $event_node);
        $url = $url_node->length !== 0 ? $url_node->item(0)->getAttribute("href") : null;
        return self::BILIETAI_ROOT_URL . $url;
    }

    /**
     * Get BilietaiEventParser object
     * @param \DOMXpath $dom
     * @param $source_url
     * @return BilietaiEventParser
     */
    public function getBilietaiEventParser(\DOMXpath $dom, $source_url)
    {
        $this->parser->setDom($dom);
        $this->parser->setSourceUrl($source_url);
        return $this->parser;
    }
}
