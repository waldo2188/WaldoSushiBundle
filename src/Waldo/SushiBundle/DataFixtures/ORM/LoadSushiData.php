<?php

namespace Waldo\SushiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


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
        // Chargement d'une liste de sushis grâce à Faker et Alice
        // Facker fournie de fausse données (nom, prénom, email, téléphone, ...)
        // Alice permet de scripter un peu plus Faker et de travailler avec des
        // objet
        $loader = new \Nelmio\Alice\Loader\Base();
        $objects = $loader->load($this->getSushis());
        $persister = new \Nelmio\Alice\ORM\Doctrine($manager);

        $persister->persist($objects);

    }


    private function getSushis() {
        $nom = <<<NOM
Menu <randomElement(\$array = array ('A','B','C','D','E','F'))><randomDigitNotNull()> <randomElement(\$array = array ('Shake Sushi','Make vegetarian','Maki Printemps Sushi','Maki Sushi','Maki Sushi Sashimi','Sashimi Moriawase '))>
NOM;
        $description = <<<DESCRIPTION
<randomDigitNotNull()> <randomElement(\$array = array ('Sushi','saumon','thon','daurade','crevette'))>, <randomDigitNotNull()> <randomElement(\$array = array ('Sushi','saumon','thon','daurade','crevette'))>, <randomDigitNotNull()> <randomElement(\$array = array ('Sushi','saumon','thon','daurade','crevette'))>, <randomDigitNotNull()> <randomElement(\$array = array ('Sushi','saumon','thon','daurade','crevette'))>, <randomDigitNotNull()> <randomElement(\$array = array ('Sushi','saumon','thon','daurade','crevette'))>\n
servis avec soupe et salade
DESCRIPTION;
        return array(
            "Waldo\SushiBundle\Entity\Sushi" => array(
                'sushi{1..5}' => array(
                    'nom' => $nom,
                    'description' => $description
                )
            )
        );
    }

}