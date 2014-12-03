<?php

namespace Woe\MapperBundle\Tests\Unit\Services\Mapper;

use Woe\MapperBundle\Services\Mapper\TextNormalizer;

class TextNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider normalizedStringsProvider
     * @param $text
     * @param array $words
     */
    public function testNormalizeReturnsArrayOfLowercasedWords($text, array $words)
    {
        $mapper = new TextNormalizer();
        $this->assertEquals($words, $mapper->normalize($text));
    }

    public function normalizedStringsProvider()
    {
        return array(
            array(
                'Eurolyga: "Neptūnas" - "Galatasaray"',
                array('eurolyg', 'neptūn', 'galatasar')
            ),
            array(
               'Moscow City Ballet - Gulbių ežeras',
                array('moscow', 'cit', 'ballet', 'gulb', 'ežer')
            ),
            array(
                'Operetė "Šuo ant šieno"',
                array('operet', 'ant', 'šien')
            ),
            array(
                '"DOMINO" teatras | premjera "VYRŲ LAIŠKAI (ELEKTRIKUI, TĖVYNEI IR KREPŠINIUI)"',
                array('domin', 'teatr', 'premjer', 'vyr', 'laišk', 'elektrik', 'tėvyn', 'krepšin')
            ),
            array(
                'V.Bagdonas ir A.Kulikauskas "Dainos sau"',
                array('bagdon', 'kulikausk', 'dain')
            ),
            array(
                'Koncertai Trakų pilyje',
                array('koncert', 'trak', 'pil')
            )
        );
    }
}
