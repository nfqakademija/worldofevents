<?php

namespace Woe\CrawlerBundle;

class BilietaiEventParser
{
    protected $source_url;

    /**
     * @param \DOMXPath $dom
     * @param string|null $source_url
     */

    public function __construct(\DOMXPath $dom, $source_url = null)
    {
        $this->dom = $dom;
        $this->source_url = $source_url;
    }

    /**
     * Get title
     * @return null|string
     */
    public function getTitle()
    {
        return $this->getNodeValueOrNull("//h1");
    }

    /**
     * Get content of a node
     *
     * @param $query
     * @param int $index
     * @return null|string
     */
    private function getNodeValueOrNull($query, $index = 0)
    {
        $node = $this->dom->query($query);
        return $node->length !== 0 ? trim($node->item($index)->nodeValue) : null;
    }

    /**
     * Get description
     * @return null|string
     */
    public function getDescription()
    {
        return $this->getNodeValueOrNull("//div[contains(@class, 'event_description')]");
    }

    /**
     * Get additional information
     * @return null|string
     */
    public function getInformation()
    {
        return $this->getNodeValueOrNull("//div[contains(@class, 'event_due_info')]//td", 1);
    }

    /**
     * Get image URL
     * @return mixed
     */
    public function getImage()
    {
        $thumbnail = $this->getNodeValueOrNull("//meta[@property='og:image']/@content");
        $image = str_replace('event-photo', 'event-big-photo', $thumbnail);
        return $image;
    }

    /**
     * Get street address
     * @return null|string
     */
    public function getAddress()
    {
        return $this->getNodeValueOrNull("//meta[@property='og:street-address']/@content");
    }

    /**
     * Get price range
     * @return null|string
     */
    private function getPriceRange()
    {
        $price_range = $this->getNodeValueOrNull("//td[contains(@class, 'price')]");

        if (!$this->isOnSale()) {
            return null;
        }

        $price_range = preg_replace('/[^\d\-\.]+/i', '', $price_range);

        return $price_range;
    }

    /**
     * Get minimum price
     * @return mixed
     */
    public function getPriceMin()
    {
        return explode('-', $this->getPriceRange())[0];
    }

    /**
     * Get maximum price
     * @return mixed
     */
    public function getPriceMax()
    {
        return explode('-', $this->getPriceRange())[1];
    }

    /**
     * Get URL of the source page
     * @return null|string
     */
    public function getSourceUrl()
    {
        return $this->source_url ? $this->source_url
                                 : $this->getNodeValueOrNull("//meta[@property='og:url']/@content");
    }

    /**
     * Set origin URL
     * @param $source_url
     */
    public function setSourceUrl($source_url)
    {
        $this->source_url = $source_url;
    }

    /**
     * Get date
     * @return \DateTime|null
     */
    public function getDate()
    {
        $date = $this->getNodeValueOrNull($this->getDateAndLocationColumns());
        // Matches 2014.11.21-23 date format
        if (preg_match('/(\d{4}\.\d{1,2}\.\d{1,2})\-\d{1,2}/', $date)) {
            // TODO: Discuss how to store this type of events
            $date = null;
        // Matches 2014-11-21 penktadienis 18:00 val. date format
        } elseif (preg_match('/(\d{4}\-\d{1,2}\-\d{1,2}) [^\s]+ (\d{1,2}:\d{1,2}) val\./', $date, $matches)) {
            $date = $matches[1] . " " . $matches[2];
            $date = new \DateTime($date, new \DateTimeZone('Europe/Vilnius'));
        } else {
            $date = null;
        }

        return $date;
    }

    /**
     * Get city
     * @return null|string
     */
    public function getCity()
    {
        return $this->getNodeValueOrNull($this->getDateAndLocationColumns(), 1);
    }

    /**
     * Get place (arena or hall name)
     * @return null|string
     */
    public function getPlace()
    {
        return $this->getNodeValueOrNull($this->getDateAndLocationColumns(), 2);
    }

    /**
     * Return true if tickets are purchasable
     * @return bool
     */
    public function isOnSale()
    {
        $price = $this->getNodeValueOrNull("//td[contains(@class, 'price')]");
        return strpos($price, 'parduota') === false;
    }

    /**
     * Get XPath for an active row with date, city and place columns
     * @return string
     */
    private function getDateAndLocationColumns()
    {
        $xpath = "//table[@id='same_events']//tr[contains(concat(' ', @class, ' '), ' act ')]/td";
        return $xpath;
    }
}
