<?php

namespace Woe\EventBundle\Services;

use Doctrine\ORM\EntityManager;
use Woe\MapperBundle\Services\Mapper\TextNormalizer;

class Search
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var TextNormalizer
     */
    private $normalizer;

    public function __construct(EntityManager $em, TextNormalizer $normalizer)
    {
        $this->em = $em;
        $this->normalizer = $normalizer;
    }

    public function getSearchResults($search_term)
    {
        $repository = $this->em->getRepository('WoeEventBundle:Event');
        $normalizedWords = $this->normalizer->normalize($search_term);

        list($normalizedWords, $dates) = $this->getDatesFromKeywords($normalizedWords);

        $events = empty($normalizedWords) ? $repository->findAllActiveSortedByDate()
                                          : $repository->findByKeywords($normalizedWords);

        return empty($dates) ? $events : $this->filterSearchResultsByDateKeywords($events, $dates);
    }

    /**
     * Separates dates keywords from regular keywords and converts them to DateTime objects
     *
     * @param $normalizedWords
     * @return array
     */
    protected function getDatesFromKeywords($normalizedWords)
    {
        $weekDays = array(
            'pirmadien' => ['monday'],
            'antradien' => ['tuesday'],
            'treciadien' => ['wednesday'],
            'ketvirtadien' => ['thursday'],
            'penktadien' => ['friday'],
            'sestadien' => ['saturday'],
            'sekmadien' => ['sunday'],
            'siandien' => ['today'],
            'rytoj' => ['tomorrow'],
            'poryt' => ['tomorrow +1 day'],
            'savaitgal' => ['saturday', 'sunday']
        );

        $dates = array();
        foreach ($weekDays as $weekDay => $dateFormats) {
            $position = array_search($weekDay, $normalizedWords);

            if ($position !== false) {
                array_splice($normalizedWords, $position, 1);
                foreach ($dateFormats as $dateFormat) {
                    $dates[] = new \DateTime($dateFormat);
                }
            }
        }

        return array($normalizedWords, $dates);
    }

    /**
     * Filter search results by date keywords
     *
     * @param array $events
     * @param array $dates
     * @return Event[]
     */
    protected function filterSearchResultsByDateKeywords($events, $dates)
    {
        return array_filter($events, function ($e) use ($dates) {
            foreach ($dates as $date) {
                if ($e->getDate()->format('Y:m:d') === $date->format('Y:m:d')) {
                    return true;
                }
            }
            return false;
        });
    }
}
