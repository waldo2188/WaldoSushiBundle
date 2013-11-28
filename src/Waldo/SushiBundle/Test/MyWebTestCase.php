<?php

namespace Waldo\SushiBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class MyWebTestCase extends WebTestCase
{

    /**
     * Allow to regenerate all the database
     *
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    protected static function generateSchema()
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
