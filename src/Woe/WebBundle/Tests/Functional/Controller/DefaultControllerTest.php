<?php

namespace Woe\WebBundle\Tests\Controller;

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

    public function testIneexPageEventElementsCount()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(3, $crawler->filter('div.event-element')->count());
    }

    /**
     * @dataProvider numberedTitlesProvider
     */
    public function testIndexPageEventsSortedById($n, $expected)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals($expected, $crawler->filter('div.event-element > div.name-plate')->eq($n)->text());
    }

    public function numberedTitlesProvider()
    {
        return array(
            array(0, 'Duis aute irure dolor in reprehenderit'),
            array(1, 'LIEPSNOJANTIS KALĖDŲ LEDAS 2014'),
            array(2, 'Andrius Mamontovas. Tas bičas iš "Fojė"')
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
}
