<?php

namespace Woe\EventBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndexPageLoadsSuccessfully()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testIndexPageTitle()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertTrue($crawler->filter('title:contains("World of Events")')->count() > 0);
    }

    public function testIndexPageEventElementsCount()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(3, $crawler->filter('div.event-card')->count());
    }

    /**
     * @dataProvider numberedTitlesProvider
     */
    public function testIndexPageEventsSortedById($n, $expected)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals($expected, $crawler->filter('div.event-card div.event-title a')->eq($n)->text());
    }

    public function numberedTitlesProvider()
    {
        return array(
            array(0, 'Duis aute irure dolor in reprehenderit'),
            array(1, 'LIEPSNOJANTIS KALĖDŲ LEDAS 2014'),
            array(2, 'Andrius Mamontovas. Tas bičas iš "Fojė"')
        );
    }

    public function testIndexPageEventsHaveDate()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals("2014-12-11 17:00", $crawler->filter('.event-card .event-info-date')->text());
    }

    public function testIndexPageEventsHaveLocationName()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals("NFQ Akademija, Vilnius", $crawler->filter('.event-card .event-info-place')->text());
    }

    public function testIndexPageEventsHaveMinimumPrice()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals("Nuo 10.00 Lt", $crawler->filter('.event-card .event-info-price')->text());
    }

    public function testIndexPageEventsHaveLinksToDetails()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals('/event/1', $crawler->filter('.event-card a')->attr('href'));
    }

    public function testIndexPageHasEventTagsListed()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertCount(3, $crawler->filter('.event-tag'));
    }

    /**
     * @dataProvider tagsWithCountProvider
     */
    public function testIndexPageHasEventTagsWithCorrectNameAndCount($name, $count)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertContains("$name $count", $crawler->filter(".event-tag-list")->text());
    }

    public function tagsWithCountProvider()
    {
        return array(
            array('geltona', '1'),
            array('juoda', '1'),
            array('raudona', '1'),
        );
    }

    public function testEventPageLoadsSuccessfully()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/event/1');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testEventPageForNonExistingEvent()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/event/999');
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function testEventPageHasEventTitle()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/event/1');
        $this->assertCount(1, $crawler->filter('h1:contains("Duis aute irure dolor in reprehenderit")'));
    }

    public function testEventPageHasEventDescription()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/event/1');
        $expected = "Lorem ipsum dolor sit amet, consectetur adipisicing elit";
        $this->assertEquals($expected, $crawler->filter('.event-description-text')->text());
    }

    public function testEventPageHasEventInformation()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/event/1');
        $expected = "Ut enim ad minim veniam, quis nostrud exercitation ullamco";
        $this->assertEquals($expected, $crawler->filter('.event-details')->text());
    }

    public function testEventPageHasEventTicketsPrice()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/event/1');
        $expected = "Kaina: 10.00 - 20.00 Lt";
        $this->assertEquals($expected, $crawler->filter('.event-price')->text());
    }

    public function testEventPageHasEventDate()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/event/1');
        $expected = "Renginio pradžia: 2014-12-11 17:00";
        $this->assertEquals($expected, $crawler->filter('.event-date')->text());
    }

    public function testEventPageHasEventPlace()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/event/1');
        $expected = "Vieta: NFQ Akademija (Konstitucijos pr. 7)";
        $this->assertEquals($expected, $crawler->filter('.event-place')->text());
    }

    public function testEventPageHasEventSourceUrl()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/event/1');
        $expected = "http://fake.dev/event/15";
        $this->assertEquals($expected, $crawler->filter('.event-tickets-button')->attr('href'));
    }

    public function testEventInformationHasLineBreaks()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/event/2');
        $this->assertCount(3, $crawler->filter('.event-details > br'));
    }

    public function testEventImageUrl()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/event/1');
        $this->assertEquals('image.jpg', $crawler->filter('.event-big-image > img')->attr('src'));
    }
}
