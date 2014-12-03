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
        return $this->lowercaseAndSplitIntoWords($text)
            ->stripWordEndings()
            ->filterShortWords()
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
            'i?[yuoėe]+[js][ie]', // matches: ioji, oji, yje, oje, uje, ėse, yse etc
            '[aeiouyąęėįų]+m?s?'  // matches: as, is, ys, ims, ams, a, į etc
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
     * Removes all non-word characters (except for spaces and dots)
     * and saves array of lowercase words
     *
     * @param $text
     * @return $this
     */
    protected function lowercaseAndSplitIntoWords($text)
    {
        mb_internal_encoding("UTF-8");

        $text = mb_strtolower($text);
        $text = preg_replace('/[^\w+ąčęėįšųūž. ]/', '', $text);
        $this->words = preg_split('/[\s.]+/', $text);

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
