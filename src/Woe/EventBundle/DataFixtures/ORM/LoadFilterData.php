<?php

namespace Woe\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Woe\EventBundle\Entity\Keyword;
use Woe\EventBundle\Entity\Tag;

class LoadFilterData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tag_keyword = [
            "juoda" => "rok",
            "geltona" => "pop",
            "raudona" => "krepšin"
        ];

        $i = 1;
        foreach ($tag_keyword as $key => $value) {
            $tag = $this->createTag($key);
            $keyword = $this->createKeyword($value, $tag);

            $this->addReference('event-tag-' . $i, $tag);

            $manager->persist($tag);
            $manager->persist($keyword);

            $i++;
        }

        $manager->flush();
    }

    /**
     * @param $name
     * @return Tag
     */
    private function createTag($name)
    {
        $tag = new Tag();
        $tag->setName($name);

        return $tag;
    }

    /**
     * @param $name
     * @param $tag
     * @return Keyword
     */
    private function createKeyword($name, $tag)
    {
        $keyword = new Keyword();
        $keyword->setName($name);
        $keyword->addTag($tag);

        return $keyword;
    }

    public function getOrder()
    {
        return 30;
    }
}