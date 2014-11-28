<?php
namespace Woe\CrawlerBundle\Services\Parser;

abstract class EventParser implements IEventParser
{
    /* @var string $source_url */
    protected $source_url;
    /* @var \DOMXpath $dom */
    protected $dom;

    /**
     * Get DOM
     * @return \DOMXpath
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * Set DOM
     * @param \DOMXPath $dom
     */
    public function setDom(\DOMXPath $dom)
    {
        $this->dom = $dom;
    }

    /**
     * Get value of a node
     * @param $xpath
     * @param int $index
     * @return null|string
     */
    public function getNodeValueOrNull($xpath, $index = 0)
    {
        $node = $this->getDom()->query($xpath);
        return $node->length !== 0 ? trim($node->item($index)->nodeValue) : null;
    }

    /**
     * Get source URL
     * @return string
     */
    public function getSourceUrl()
    {
        return $this->source_url;
    }

    /**
     * Set source URL
     * @param $source_url
     */
    public function setSourceUrl($source_url)
    {
        $this->source_url = $source_url;
    }

    /**
     * Check required fields' presence
     * @return bool
     */
    public function isValid()
    {
        return $this->getTitle() && $this->getDate() &&
            $this->isOnSale() && $this->getPlace();
    }
}
