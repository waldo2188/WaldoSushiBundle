<?php

namespace Waldo\SushiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Waldo\SushiBundle\Entity\Sushi;


/**
 * Charge les données sur les sushis
 */
class LoadSushiData implements FixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $loader = new \Nelmio\Alice\Loader\Base();
        $objects = $loader->load($this->getSushis());
        $persister = new \Nelmio\Alice\ORM\Doctrine($manager);

        $persister->persist($objects);

//        $userAdmin = new User();
//        $userAdmin->setUsername('admin');
//        $userAdmin->setPassword('test');
//
//        $manager->persist($userAdmin);
//        $manager->flush();
    }


    private function getSushis() {
        return array(
            "Waldo\SushiBundle\Entity\Sushi" => array(
                'sushi{1..5}' => array(
                    'nom' => '<sentence($nbWords = 3)>',
                    'description' => '<paragraph($nbSentences = 1)>'
                )
            )
        );
    }

}