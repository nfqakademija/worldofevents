<?php

namespace Woe\CrawlerBundle;

class BilietaiCrawler
{
    const BILIETAI_ROOT_URL = "http://www.bilietai.lt/";

    private $page_index = 1;
    /* @var \DOMXPath $current_page */
    private $current_page;

    /**
     * Download current page and build DOM
     */
    public function fetchCurrentPage()
    {
        $url = self::BILIETAI_ROOT_URL . "lt/category/0/" . $this->page_index;
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
        $xpath = "//div[contains(@class, 'item_name')]/a[starts-with(@href, 'lt/event/')]";
        /* @var \DOMElement $node */
        foreach ($this->current_page->query($xpath) as $node) {
            $event_url = self::BILIETAI_ROOT_URL . $node->getAttribute("href");
            $event = new BilietaiEventParser($this->fetchUrl($event_url));

            if ($this->isValidEvent($event)) {
                $event->setSourceUrl($event_url);
                $events[] = $event;
            }
        }
        return $events;
    }

    /**
     * @param BilietaiEventParser $event
     * @return bool
     */
    private function isValidEvent($event)
    {
        return $event->isValid();
    }

    /**
     * Check whether the current page has 'Next' button
     *
     * @return bool
     */
    public function hasNextPage()
    {
        $xpath = "//*[contains(concat(' ', @class, ' '), ' next ')]";
        return $this->current_page && $this->current_page->query($xpath)->length !== 0;
    }

    /**
     * Increment current page index
     */
    public function nextPage()
    {
        $this->page_index += 1;
    }
}
