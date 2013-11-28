<?php

namespace Waldo\SushiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Waldo\SushiBundle\Test\MyWebTestCase;

/**
 * @group fonctionnal
 */
class UtilisateurControllerTest extends MyWebTestCase
{

    /**
     * Test Liste:
     * - code de retour de la page est 200
     * - le formulaire contient la possibilité de saisir un mot de passe
     */
    public function testShouldShowPaswordField()
    {
        $client = self::createClient();

        $client->request('GET', '/utilisateur/edit');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->getCrawler();

        $this->assertEquals(1, $crawler->filterXPath("//label[contains(text(),'Mot de passe')]")->count());
        $this->assertEquals(1, $crawler->filterXPath("//label[contains(text(),'Re-saisir votre mot de passe')]")->count());
    }

    /**
     * Test Liste:
     * - code de retour de la page 302
     * - la page de redirection est la page du formulaire + l'id de l'élément créé
     * - la redirection même à une page renvoyant un code 200
     * - que le formulaire contienne bien les bonnes données
     * - que le formulaire n'affiche plus les champs mot de passe
     */
    public function testShouldSaveAUser()
    {
        $client = self::createClient();

        self::generateSchema();

        // Le contenu du CsrfToken doit être le même que le nom du type de formulaire
        $csrfToken = $client->getContainer()->get('form.csrf_provider')->generateCsrfToken('utilisateur');

        $client->request('POST', '/utilisateur/edit', array(
            'utilisateur' => array(
                'username' => 'arthur.dent',
                'email' => 'arthur.dent@42.fr',
                'adresse' => 'Un text sans importance',
                'password' => array('password' =>
                    array('first' => 'aaaaa.1A',
                          'second' => 'aaaaa.1A')
                    ),
                '_token' => $csrfToken,
            )
        ));

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertRegExp("/^\/utilisateur\/edit\/([0-9]+)$/", $client->getResponse()->getTargetUrl());

        $crawler = $client->followRedirect();

        $this->assertEquals(0, $crawler->filterXPath("//label[contains(text(),'Mot de passe')]")->count());
        $this->assertEquals(0, $crawler->filterXPath("//label[contains(text(),'Re-saisir votre mot de passe')]")->count());
    }

    /**
     * Test Liste:
     * - code de retour de la page 200
     * - erreur lié à la saisie d'un mauvais mot de passe
     *
     * Ce test permet de vérifier si les tests du formulaire se font bien toujours
     * en cascade
     */
    public function testShoulFailForPassword()
    {

        $client = self::createClient();

        self::generateSchema();

        // Le contenu du CsrfToken doit être le même que le nom du type de formulaire
        $csrfToken = $client->getContainer()->get('form.csrf_provider')->generateCsrfToken('utilisateur');

        $crawler = $client->request('POST', '/utilisateur/edit', array(
            'utilisateur' => array(
                'username' => 'arthur.dent',
                'email' => 'arthur.dent@42.fr',
                'adresse' => 'Un text sans importance',
                'password' => array('password' =>
                    array('first' => 'aaaa',
                          'second' => 'aaaa')
                    ),
                '_token' => $csrfToken,
            )
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Votre mot de passe doit être d\'au moins 8 characteres")')->count());
        
    }

    
}
