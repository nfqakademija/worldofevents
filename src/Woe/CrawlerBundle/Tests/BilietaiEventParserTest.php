<?php


namespace Woe\CrawlerBundle\Tests;

use Woe\CrawlerBundle\BilietaiEventParser;

class BilietaiEventParserTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->loadFixtureFromFile("bilietai_event_page_with_valid_information.html");
    }

    public function testEventTitle()
    {
        $parser = $this->loadFixtureFromFile("bilietai_event_page_with_valid_information.html");
        $expected = 'Andrius Mamontovas. Tas bičas iš "Fojė"';
        $actual = $parser->getTitle();
        $this->assertEquals($expected, $actual);
    }

    public function testEventDescription()
    {
        $parser = $this->loadFixtureFromFile("bilietai_event_page_with_valid_information.html");
        $prefix = 'Mano vardas - Andrius Mamontovas. Kažkada grojau grupėje Foje.';
        $actual = $parser->getDescription();
        $this->assertStringStartsWith($prefix, $actual);
    }

    public function testEventInformation()
    {
        $parser = $this->loadFixtureFromFile("bilietai_event_page_with_valid_information.html");
        $prefix = 'Svarbi informacija';
        $actual = $parser->getInformation();
        $this->assertStringStartsWith($prefix, $actual);
    }

    public function testEventImage()
    {
        $parser = $this->loadFixtureFromFile("bilietai_event_page_with_valid_information.html");
        $expected = 'http://www.bilietai.lt/event-big-photo/22830.png';
        $actual = $parser->getImage();
        $this->assertEquals($expected, $actual);
    }

    public function testEventStreetAddress()
    {
        $parser = $this->loadFixtureFromFile("bilietai_event_page_with_valid_information.html");
        $expected = 'Kernavės g. 84, Vilnius';
        $actual = $parser->getAddress();
        $this->assertEquals($expected, $actual);
    }

    public function testEventPriceMin()
    {
        $parser = $this->loadFixtureFromFile("bilietai_event_page_with_valid_information.html");
        $expected = '73.00';
        $actual = $parser->getPriceMin();
        $this->assertEquals($expected, $actual);
    }

    public function testEventPriceMax()
    {
        $parser = $this->loadFixtureFromFile("bilietai_event_page_with_valid_information.html");
        $expected = '103.00';
        $actual = $parser->getPriceMax();
        $this->assertEquals($expected, $actual);
    }

    public function testSourceUrlSetLocally()
    {
        $parser = $this->loadFixtureFromFile("bilietai_event_page_with_valid_information.html");
        $expected = 'http://www.bilietai.lt/event/00000';
        $parser->setSourceUrl($expected);
        $actual = $parser->getSourceUrl();
        $this->assertEquals($expected, $actual);
    }

    public function testSourceUrlFromTheDom()
    {
        $parser = $this->loadFixtureFromFile("bilietai_event_page_with_valid_information.html");
        $expected = 'http://www.bilietai.lt/event/22830';
        $actual = $parser->getSourceUrl();
        $this->assertEquals($expected, $actual);
    }

    public function testEventDate()
    {
        $parser = $this->loadFixtureFromFile("bilietai_event_page_with_valid_information.html");
        $expected = new \DateTime('2014-12-26 19:00', new \DateTimeZone('Europe/Vilnius'));
        $actual = $parser->getDate();
        $this->assertEquals($expected, $actual);
    }

    public function testEventDateRange()
    {
        $parser = $this->loadFixtureFromFile("bilietai_event_page_date_range.html");
        $this->assertNull($parser->getDate());
    }

    public function testEventCity()
    {
        $parser = $this->loadFixtureFromFile("bilietai_event_page_with_valid_information.html");
        $expected = 'Vilnius';
        $actual = $parser->getCity();
        $this->assertEquals($expected, $actual);
    }

    public function testEventPlace()
    {
        $parser = $this->loadFixtureFromFile("bilietai_event_page_with_valid_information.html");
        $expected = 'Naujoji koncertų salė';
        $actual = $parser->getPlace();
        $this->assertEquals($expected, $actual);
    }

    public function testEventIsValid()
    {
        $parser = $this->loadFixtureFromFile("bilietai_event_page_with_valid_information.html");
        $this->assertTrue($parser->isValid());
    }

    public function testEventIsOnSale()
    {
        $parser = $this->loadFixtureFromFile("bilietai_event_page_with_valid_information.html");
        $this->assertTrue($parser->isOnSale());
    }

    public function testEventIsNotOnSale()
    {
        $parser = $this->loadFixtureFromFile("bilietai_event_page_sold_out.html");
        $this->assertFalse($parser->isOnSale());
    }

    private function loadFixtureFromFile($file)
    {
        $html = file_get_contents(__DIR__ . "/Fixtures/" . $file);
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $domxpath = new \DOMXPath($dom);
        return new BilietaiEventParser($domxpath);
    }
}