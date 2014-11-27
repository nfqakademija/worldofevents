<?php

namespace Woe\CrawlerBundle\Services\Crawler;

class BilietaiCrawler extends Crawler
{
    /**
     * Get URL of the current page
     * @return string
     */
    public function getCurrentPageUrl()
    {
        return $this->getSiteRootUrl() . "lt/category/0/" . $this->page_index;
    }

    /**
     * @return string
     */
    public function getSiteRootUrl()
    {
        return "http://www.bilietai.lt/";
    }

    /**
     * @return string
     */
    public function getXpathNextPageButton()
    {
        $xpath = "//*[contains(concat(' ', @class, ' '), ' next ')]";
        return $xpath;
    }

    /**
     * @return string
     */
    public function getXpathEventNodes()
    {
        $xpath = "//td[contains(concat(' ', @class, ' '), ' list_item ')]";
        return $xpath;
    }

    /**
     * @return string
     */
    public function getXpathEventPrice()
    {
        $xpath = "div[contains(@class, 'wf')]//div[contains(@class, 'price_padd')]/strong";
        return $xpath;
    }

    /**
     * @return string
     */
    public function getXpathEventUrl()
    {
        $xpath = "div[contains(@class, 'list_item_cont')]//a[starts-with(@href, 'lt/event/')]";
        return $xpath;
    }
}
