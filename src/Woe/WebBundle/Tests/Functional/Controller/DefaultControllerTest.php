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
}
