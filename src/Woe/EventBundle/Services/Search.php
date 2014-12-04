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
        $normalized_words = $this->normalizer->normalize($search_term);
        $repository = $this->em->getRepository('WoeEventBundle:Event');
        return $repository->findByKeywords($normalized_words);
    }
}
