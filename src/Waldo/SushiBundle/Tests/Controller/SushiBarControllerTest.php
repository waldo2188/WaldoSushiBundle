<?php

namespace Waldo\SushiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Waldo\SushiBundle\Test\MyWebTestCase;

/**
 * @group fonctionnal
 */
class SushiBarControllerTest extends MyWebTestCase
{

    /**
     *  Test Liste :
     * - Code retour de la page = 200
     * - La réponse contien une liste
     * - Le nombre de li = le nombre de sushi dans la base
     */
    public function testShouldDisplayTheSushiList()
    {
        $client = self::createClient();

        self::generateSchema();

        $loader = new \Nelmio\Alice\Loader\Base();
        $objects = $loader->load($this->getSushis());
        $persister = new \Nelmio\Alice\ORM\Doctrine(
        static::$kernel->getContainer()->get('doctrine.orm.entity_manager')
                );

        $persister->persist($objects);
        

        $crawler = $client->request('GET', '/sushi-bar/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(1, $crawler->filter("li")->count());
    }

    /**
     * Test Liste:
     * - Code de retour de la page 302 (redirection après réusite de l'enregistrement)
     * - la page de redirection est la page du formulaire + l'id de l'élément créé
     * - la redirection même à une page renvoyant un code 200
     * - que le formulaire contienne bien les bonnes données
     */
    public function testShouldSaveANewSushi() {
        $client = self::createClient();

        // Le contenu du CsrfToken doit être le même que le nom du type de formulaire
        $csrfToken = $client->getContainer()->get('form.csrf_provider')->generateCsrfToken('sushi');

        $client->request('POST', '/sushi-bar/edit-sushi', array(
            'sushi' => array(
                'nom' => 'Menu A42',
                'description' => 'Un text sans importance',
                '_token' => $csrfToken,
            )
        ));
        
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertRegExp("/^\/sushi-bar\/edit-sushi\/([0-9]+)$/", $client->getResponse()->getTargetUrl());

        $crawler = $client->followRedirect();


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("Menu A42", $crawler->filter('input[id="sushi_nom"]')->attr("value"));
        $this->assertEquals("Un text sans importance", $crawler->filter('textarea[id="sushi_description"]')->text());
    }

    /**
     * Test Liste:
     * - Code de retour de la page 200
     * - La page contien des erreurs de validation de formulaire
     */
    public function testShouldNotSaveANewSushi() {
        $client = self::createClient();

        // Le contenu du CsrfToken doit être le même que le nom du type de formulaire
        $csrfToken = $client->getContainer()->get('form.csrf_provider')->generateCsrfToken('sushi');

        $client->request('POST', '/sushi-bar/edit-sushi', array(
            'sushi' => array(
                'nom' => '  ',
                'description' => '  ',
                '_token' => $csrfToken,
            )
        ));

        $crawler = $client->getCrawler();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("", $crawler->filter('input[id="sushi_nom"]')->attr("value"));
        $this->assertEquals("", $crawler->filter('textarea[id="sushi_description"]')->text());

        $this->assertContains("Un nom doit être défini", $crawler->filter('form')->text());
        $this->assertContains("Une description doit être défini.", $crawler->filter('form')->text());
    }

    
    public function testShouldThrowANotFundException() {

        $client = self::createClient();

        self::generateSchema();
        
        $client->request('GET', '/sushi-bar/edit-sushi/404');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
            
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
