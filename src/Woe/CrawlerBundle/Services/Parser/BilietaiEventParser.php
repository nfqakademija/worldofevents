<?php

namespace Woe\CrawlerBundle\Services\Parser;

class BilietaiEventParser extends EventParser
{
    /**
     * Get title
     * @return null|string
     */
    public function getTitle()
    {
        return $this->getNodeValueOrNull("//h1");
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
        if (!$this->isOnSale()) {
            return null;
        }

        $price_range = $this->getNodeValueOrNull("//td[contains(@class, 'price')]");
        $price_range = preg_replace('/[^\d\-\.]+/i', '', $price_range);

        $prices = explode('-', $price_range);

        return count($prices) === 2 ? $prices : null;
    }

    /**
     * Get minimum price
     * @return null|string
     */
    public function getPriceMin()
    {
        $price_range = $this->getPriceRange();
        return !is_null($price_range) ? $price_range[0] : null;
    }

    /**
     * Get maximum price
     * @return null|string
     */
    public function getPriceMax()
    {
        $price_range = $this->getPriceRange();
        return !is_null($price_range) ? $price_range[1] : null;
    }

    /**
     * Get date
     * @return \DateTime|null
     */
    public function getDate()
    {
        $date = $this->getDateAndLocationColumn();
        // Matches 2014.11.21-23 date format
        if (preg_match('/(\d{4}\.\d{1,2}\.\d{1,2})\-\d{1,2}/', $date, $matches)) {
            $date = str_replace('.', '-', $matches[1]);
            $date = new \DateTime($date, new \DateTimeZone('Europe/Vilnius'));
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
        return $this->getDateAndLocationColumn(1);
    }

    /**
     * Get place (arena or hall name)
     * @return null|string
     */
    public function getPlace()
    {
        return $this->getDateAndLocationColumn(2);
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
     * Get XPath for an active (or the first) row with date, city and place columns
     * @param $column
     * @return string
     */
    private function getDateAndLocationColumn($column = 0)
    {
        $xpath = "//table[@id='same_events']//tr[contains(concat(' ', @class, ' '), ' act ')]/td";
        $node = $this->getNodeValueOrNull($xpath, $column);

        if (is_null($node)) {
            $xpath = "//table[@id='same_events']//tr/td";
            $node = $this->getNodeValueOrNull($xpath, $column);
        }

        return $node;
    }
}
