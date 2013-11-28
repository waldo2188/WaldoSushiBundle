<?php

namespace Waldo\SushiBundle\Tests\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use Waldo\SushiBundle\Form\Type\SushiType;
use Waldo\SushiBundle\Entity\Sushi;

/**
 * Permet de tester un type de formulaire
 *
 * @see http://symfony.com/doc/current/cookbook/form/unit_testing.html
 */
class SushiTypeTest extends TypeTestCase
{

    public function testSubmitValidData()
    {
        $formData = array(
            "nom" => "un Nom",
            "description" => "une description"
        );

        $type = new SushiType();
        $form = $this->factory->create($type);

        $object = new Sushi($formData['nom'], $formData['description']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach(array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }        
    }

}
