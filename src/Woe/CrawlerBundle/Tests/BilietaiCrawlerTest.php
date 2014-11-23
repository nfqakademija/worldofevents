<?php

namespace Woe\CrawlerBundle\Tests;

use Woe\CrawlerBundle\BilietaiCrawler;

class BilietaiCrawlerTest extends \PHPUnit_Framework_TestCase
{
    public function testFetchUrlReturnsCorrectObjectType()
    {
        $crawler = new BilietaiCrawler();
        $domxpath = $crawler->fetchUrl(__DIR__ . "/Fixtures/" . "bilietai_event_list.html");
        $this->assertInstanceOf('DOMXPath', $domxpath);
    }

    public function testGetCurrentPageUrlFirstPage()
    {
        $crawler = new BilietaiCrawler();
        $expected = 'http://www.bilietai.lt/lt/category/0/1';
        $actual = $crawler->getCurrentPageUrl();
        $this->assertEquals($expected, $actual);
    }

    public function testGetCurrentPageUrlSecondPage()
    {
        $crawler = new BilietaiCrawler();
        $expected = 'http://www.bilietai.lt/lt/category/0/2';
        $crawler->nextPage();
        $actual = $crawler->getCurrentPageUrl();
        $this->assertEquals($expected, $actual);
    }

    public function testGetCurrentPageIsNullByDefault()
    {
        $crawler = new BilietaiCrawler();
        $this->assertNull($crawler->getCurrentPage());
    }

    public function testFetchCurrentPage()
    {
        $crawler = $this->getMockBuilder('Woe\CrawlerBundle\BilietaiCrawler')
            ->setMethods(array('getCurrentPageUrl'))
            ->getMock();
        $crawler->method('getCurrentPageUrl')->willReturn(__DIR__ . "/Fixtures/" . 'bilietai_event_list.html');
        $crawler->fetchCurrentPage();
        $this->assertNotEmpty($crawler->getCurrentPage()->query('//html')->length);
    }

    public function testGetEvents()
    {
        $crawler = $this->getMockBuilder('Woe\CrawlerBundle\BilietaiCrawler')
            ->setMethods(array('getCurrentPageUrl', 'getEventUrl'))
            ->getMock();
        $crawler->method('getCurrentPageUrl')->willReturn(__DIR__ . "/Fixtures/" . 'bilietai_event_list.html');
        $crawler->method('getEventUrl')->willReturn(__DIR__ . "/Fixtures/" . 'empty_file.html');
        $crawler->fetchCurrentPage();
        $this->assertCount(4, $crawler->getEvents());
    }

    public function testFirstPageHasNextPage()
    {
        $crawler = $this->getMockBuilder('Woe\CrawlerBundle\BilietaiCrawler')
            ->setMethods(array('getCurrentPageUrl'))
            ->getMock();
        $crawler->method('getCurrentPageUrl')->willReturn(__DIR__ . "/Fixtures/" . 'bilietai_event_list.html');
        $crawler->fetchCurrentPage();
        $this->assertTrue($crawler->hasNextPage());
    }

    public function testLastPageDoesntHaveNextPage()
    {
        $crawler = $this->getMockBuilder('Woe\CrawlerBundle\BilietaiCrawler')
            ->setMethods(array('getCurrentPageUrl'))
            ->getMock();
        $crawler->method('getCurrentPageUrl')->willReturn(__DIR__ . "/Fixtures/" . 'bilietai_event_list_last_page.html');
        $crawler->fetchCurrentPage();
        $this->assertFalse($crawler->hasNextPage());
    }

    // test isOnSale and getEventUrl
}
