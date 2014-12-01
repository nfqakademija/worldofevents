<?php

namespace Woe\CrawlerBundle\Tests\Unit\Services\Crawler;

use Woe\CrawlerBundle\Services\Crawler\BilietaiCrawler;

class BilietaiCrawlerTest extends \PHPUnit_Framework_TestCase
{
    public function testFetchUrlReturnsCorrectObjectType()
    {
        $parser = $this->getMock('Woe\CrawlerBundle\Services\Parser\BilietaiEventParser');
        $crawler = new BilietaiCrawler($parser);
        $domxpath = $crawler->fetchUrl(__DIR__ . "/Fixtures/" . "bilietai_event_list.html");
        $this->assertInstanceOf('DOMXPath', $domxpath);
    }

    public function testGetCurrentPageUrlFirstPage()
    {
        $parser = $this->getMock('Woe\CrawlerBundle\Services\Parser\BilietaiEventParser');
        $crawler = new BilietaiCrawler($parser);
        $expected = 'http://www.bilietai.lt/lt/category/0/1';
        $actual = $crawler->getCurrentPageUrl();
        $this->assertEquals($expected, $actual);
    }

    public function testGetCurrentPageUrlSecondPage()
    {
        $parser = $this->getMock('Woe\CrawlerBundle\Services\Parser\BilietaiEventParser');
        $crawler = new BilietaiCrawler($parser);
        $expected = 'http://www.bilietai.lt/lt/category/0/2';
        $crawler->nextPage();
        $actual = $crawler->getCurrentPageUrl();
        $this->assertEquals($expected, $actual);
    }

    public function testGetCurrentPageIsNullByDefault()
    {
        $parser = $this->getMock('Woe\CrawlerBundle\Services\Parser\BilietaiEventParser');
        $crawler = new BilietaiCrawler($parser);
        $this->assertNull($crawler->getCurrentPage());
    }

    public function testFetchCurrentPage()
    {
        $crawler = $this->getMockBuilder('Woe\CrawlerBundle\Services\Crawler\BilietaiCrawler')
            ->disableOriginalConstructor()
            ->setMethods(array('getCurrentPageUrl'))
            ->getMock();
        $crawler->method('getCurrentPageUrl')->willReturn(__DIR__ . "/Fixtures/" . 'bilietai_event_list.html');
        $crawler->fetchCurrentPage();
        $this->assertNotEmpty($crawler->getCurrentPage()->query('//html')->length);
    }

    public function testGetEventsReturnsOnlyActiveEvents()
    {
        $parser = $this->getMock('Woe\CrawlerBundle\Services\Parser\BilietaiEventParser');
        $crawler = $this->getMockBuilder('Woe\CrawlerBundle\Services\Crawler\BilietaiCrawler')
            ->setConstructorArgs(array($parser))
            ->setMethods(array('getCurrentPageUrl', 'getEventUrl'))
            ->getMock();
        $crawler->method('getCurrentPageUrl')->willReturn(__DIR__ . "/Fixtures/" . 'bilietai_event_list.html');
        $crawler->method('getEventUrl')->willReturn(__DIR__ . "/Fixtures/" . 'empty_file.html');
        $crawler->fetchCurrentPage();
        $this->assertCount(4, $crawler->getEvents());
    }

    public function testEventParserObjectsShouldNotBeTheSame()
    {
        $parser = $this->getMock('Woe\CrawlerBundle\Services\Parser\BilietaiEventParser');
        $crawler = $this->getMockBuilder('Woe\CrawlerBundle\Services\Crawler\BilietaiCrawler')
            ->setConstructorArgs(array($parser))
            ->setMethods(array('getCurrentPageUrl', 'getEventUrl'))
            ->getMock();
        $crawler->method('getCurrentPageUrl')->willReturn(__DIR__ . "/Fixtures/" . 'bilietai_event_list.html');
        $crawler->method('getEventUrl')->willReturn(__DIR__ . "/Fixtures/" . 'empty_file.html');
        $crawler->fetchCurrentPage();
        $events = $crawler->getEvents();
        $this->assertNotSame($events[0], $events[1]);
    }

    public function testFirstPageHasNextPage()
    {
        $crawler = $this->getMockBuilder('Woe\CrawlerBundle\Services\Crawler\BilietaiCrawler')
            ->disableOriginalConstructor()
            ->setMethods(array('getCurrentPageUrl'))
            ->getMock();
        $crawler->method('getCurrentPageUrl')->willReturn(__DIR__ . "/Fixtures/" . 'bilietai_event_list.html');
        $crawler->fetchCurrentPage();
        $this->assertTrue($crawler->hasNextPage());
    }

    public function testLastPageShouldNotHaveNextPageLink()
    {
        $crawler = $this->getMockBuilder('Woe\CrawlerBundle\Services\Crawler\BilietaiCrawler')
            ->disableOriginalConstructor()
            ->setMethods(array('getCurrentPageUrl'))
            ->getMock();
        $crawler->method('getCurrentPageUrl')->willReturn(__DIR__ . "/Fixtures/" . 'bilietai_event_list_last_page.html');
        $crawler->fetchCurrentPage();
        $this->assertFalse($crawler->hasNextPage());
    }

    public function testEventUrlOfADomNode()
    {
        $crawler = $this->getMockBuilder('Woe\CrawlerBundle\Services\Crawler\BilietaiCrawler')
            ->disableOriginalConstructor()
            ->setMethods(array('getCurrentPageUrl'))
            ->getMock();
        $crawler->method('getCurrentPageUrl')->willReturn(__DIR__ . "/Fixtures/" . 'bilietai_event_list.html');
        $crawler->fetchCurrentPage();

        $node = $crawler->getCurrentPage()->query("//td[contains(@class, 'list_item')]")->item(0);
        $actual = $crawler->getEventUrl($node);

        $this->assertEquals('http://www.bilietai.lt/lt/event/22289', $actual);
    }
}
