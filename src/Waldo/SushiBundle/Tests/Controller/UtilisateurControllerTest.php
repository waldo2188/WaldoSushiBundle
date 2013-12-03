<?php

namespace Waldo\SushiBundle\Tests\Controller;

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
                    array('first' => 'aaaaa..11AA',
                          'second' => 'aaaaa..11AA')
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


    /**
     * Test Liste:
     * - code de retour de la page 200
     * - liste de 4 utilisateurs
     */
    public function testShouldShowUserList()
    {
        $client = self::createClient();

        self::generateSchema();

        $loader = new \Nelmio\Alice\Loader\Base();
        $objects = $loader->load($this->getUtilisateurs());
        $persister = new \Nelmio\Alice\ORM\Doctrine(
            static::$kernel->getContainer()->get('doctrine.orm.entity_manager')
                );

        $persister->persist($objects);

        $crawler = $client->request('GET', '/utilisateur/show/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(4, $crawler->filter("tr")->count());
        
    }

    /**
     * Test Liste:
     * - code de retour de la page 302
     * - mot de passe différent du précédent
     * - autre donné inchangé
     */
    public function testShouldChangePasswordAndNotTheOtherData()
    {
        $client = self::createClient();

        self::generateSchema();

        $utilisateur = new \Waldo\SushiBundle\Entity\Utilisateur();
        $utilisateur->setUsername("arthur.dent")
                ->setEmail("arthur.dent@42.fr")
                ->setSalt("SaltIsBadForHealth")
                ->setPassword("WhatAWonderFullPassword");

        $persister = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $persister->persist($utilisateur);
        $persister->flush();

         // Le contenu du CsrfToken doit être le même que le nom du type de formulaire
        $csrfToken = $client->getContainer()->get('form.csrf_provider')->generateCsrfToken('utilisateur_password');

        $client->request('POST', '/utilisateur/edit-password/' . $utilisateur->getId(), array(
            'utilisateur_password' => array(
                'password' => array(
                    'first' => "AA11..aa",
                    'second' => "AA11..aa"
                ),
                '_token' => $csrfToken,
            )
        ));

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        
        $this->assertEquals($utilisateur->getUsername(), $crawler->filterXPath('//td[contains(text(),"' . $utilisateur->getUsername() . '")]')->text());
        $this->assertEquals($utilisateur->getSalt(), $crawler->filterXPath('//td[contains(text(),"' . $utilisateur->getSalt() . '")]')->text());
        $this->assertEquals("AA11..aa", $crawler->filterXPath('//td[contains(text(),"' . $utilisateur->getPassword() . '")]')->text());
        
    }

    private function getUtilisateurs() {
        return array(
            "Waldo\SushiBundle\Entity\Utilisateur" => array(
                'utilisateur{1..5}' => array(
                    'username' => '<firstName()>.<lastName()>',
                    'password' => '<paragraph($nbSentences = 1)>',
                    'salt' => '<paragraph($nbSentences = 1)>',
                    'email' => '<safeEmail()>'
                )
            )
        );
    }
}
