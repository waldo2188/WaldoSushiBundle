<?php

namespace Waldo\SushiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test Liste :
 * - Code retour de la page = 200
 * - La réponse contien une liste
 * - Le nombre de li = le nombre de sushi dans la base
 *
 * @group fonctionnal
 */
class DefaultControllerTest extends WebTestCase
{

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


    /**
     * Allow to regenerate all the database
     *
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public static function generateSchema()
    {
        if(null === static::$kernel) {
            static::$kernel = static::createKernel();
        }

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = static::$kernel->getContainer()->get("doctrine.orm.entity_manager");

        // Get the metadata of the application to create the schema.
        $metadata = $em->getMetadataFactory()->getAllMetadata();

        if(!empty($metadata)) {
            $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
            $tool->dropDatabase();
            $tool->createSchema($metadata);
        } else {
            throw new \Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
        }
    }
}
