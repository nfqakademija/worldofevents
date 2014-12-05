<?php

namespace Woe\MapperBundle\Services\Mapper;

class TextNormalizer
{
    protected $words = [];

    /**
     * Get normalized array of words from a string
     *
     * @param $text
     * @return array
     */
    public function normalize($text)
    {
        return $this->convertAndSplitIntoWords($text)
            ->stripWordEndings()
            ->filterShortWords()
            ->filterDuplicates()
            ->getWords();
    }

    /**
     * Removes lithuanian word endings
     *
     * @return $this
     */
    protected function stripWordEndings()
    {
        $word_endings = array(
            'i?[yuoe]+[js][ie]', // matches: ioji, oji, yje, oje, uje, ėse, yse etc
            '[aeiouy]+m?s?'  // matches: as, is, ys, ims, ams, a, į etc
        );

        $regex = '/' . join('|', $word_endings) . '$/';
        $this->words = preg_replace($regex, '', $this->words, 1);

        return $this;
    }

    /**
     * Filters out words shorter than 2 characters (inclusive)
     *
     * @return $this
     */
    protected function filterShortWords()
    {
        $longer_words = array_filter($this->words, function ($e) {
            return strlen($e) > 2;
        });

        $this->words = array_values($longer_words);

        return $this;
    }

    /**
     * Removes or replaces all non-word characters (except for spaces and dots),
     * and saves array of lowercase words
     *
     * @param $text
     * @return $this
     */
    protected function convertAndSplitIntoWords($text)
    {
        mb_internal_encoding("UTF-8");
        $text = mb_strtolower($text);
        $text = str_replace(
            array('ą', 'č', 'ę', 'ė', 'į', 'š', 'ų', 'ū', 'ž'),
            array('a', 'c', 'e', 'e', 'i', 's', 'u', 'u', 'z'),
            $text
        );
        $text = preg_replace('/[^\w+. ]/', '', $text);
        $this->words = preg_split('/[\s.]+/', $text);

        return $this;
    }

    /**
     * Removes duplicated words
     *
     * @return $this
     */
    public function filterDuplicates()
    {
        $this->words = array_unique($this->words);

        return $this;
    }

    /**
     * Get words
     *
     * @return array
     */
    public function getWords()
    {
        return $this->words;
    }
}
