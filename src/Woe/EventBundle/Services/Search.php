<?php

namespace Woe\EventBundle\Services;

use Doctrine\ORM\EntityManager;
use Woe\MapperBundle\Services\Mapper\TextNormalizer;

class Search
{
    /**
     * Data structure for generating date object from keywords
     * @var array
     */
    protected $weekDays = array(
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

    /**
     * Get search results for $search_term
     *
     * @param string $search_term
     * @return \Woe\EventBundle\Entity\Event[]
     */
    public function getSearchResults($search_term)
    {
        $repository = $this->em->getRepository('WoeEventBundle:Event');
        $normalizedWords = $this->normalizer->normalize($search_term);

        list($normalizedWords, $dates) = $this->getDatesFromKeywords($normalizedWords);

        $events = empty($normalizedWords) ? $repository->findAllActiveSortedByDate()
                                          : $repository->findByKeywords($normalizedWords);

        $test = $this->filterSearchResultsByDateKeywords($events, $dates);
        return empty($dates) ? $events : $this->filterSearchResultsByDateKeywords($events, $dates);
    }

    /**
     * Separates dates keywords from regular keywords and converts them to DateTime objects
     *
     * @param array $normalizedWords
     * @return array
     */
    protected function getDatesFromKeywords(array $normalizedWords)
    {
        $dates = array();
        foreach ($this->weekDays as $weekDay => $dateFormats) {
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
     * @param \Woe\EventBundle\Entity\Event[] $events
     * @param \DateTime[] $dates
     * @return \Woe\EventBundle\Entity\Event[]
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
