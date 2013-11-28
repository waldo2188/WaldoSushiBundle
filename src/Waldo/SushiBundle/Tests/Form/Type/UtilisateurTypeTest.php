<?php

namespace Waldo\SushiBundle\Tests\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\PreloadedExtension;

/**
 * Test du type de formulaire UtilisateurType
 *
 * @see http://symfony.com/doc/current/cookbook/form/unit_testing.html
 */
class UtilisateurTypeTest extends TypeTestCase
{

    /**
     * Preload deu type MotDePasseType
     * @return type
     */
    protected function getExtensions()
    {
        $passwordType = new \Waldo\SushiBundle\Form\Type\MotDePasseType();

        // Le validator est nécessaire car nous utilison un champs de type "repeated"
        $validator = \Symfony\Component\Validator\Validation::createValidatorBuilder()
                ->enableAnnotationMapping()
                ->getValidator();


        return array(
            new \Symfony\Component\Form\Extension\Validator\ValidatorExtension($validator),
            new PreloadedExtension(
                    array(
                $passwordType->getName() => $passwordType
                    ), array()
            )
        );
    }

    /**
     * Ce test permet de valider que les champs de $formData sont bien ceux qui
     * seront par le formulaire
     */
    public function testSubmitValidData()
    {
        $formData = array(
            "username" => "arthur-dent",
            "email" => "arthur-dent@42.uk",
            "adresse" => "une adresse",
            "password" => array('password' => array('first' => "unMotDePasse", "second" => "unMotDePasse"))
        );


        $utilisateurType = new \Waldo\SushiBundle\Form\Type\UtilisateurType();
        $form = $this->factory->create($utilisateurType);

        $object = new \Waldo\SushiBundle\Entity\Utilisateur();
        $object->setUsername($formData['username'])
                ->setEmail($formData['email'])
                ->setPassword($formData['password']['password']['first'])
                ->setAdresse($formData['adresse']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    /**
     * Ce test pemert de valider qu'un formulaire pour  un utilisateur qui à un
     * id n'affiche pas les champs de mot de passe
     */
    public function testSubmitValidWhitoutPasswordData()
    {
        $formData = array(
            "username" => "arthur-dent",
            "email" => "arthur-dent@42.uk",
            "adresse" => "une adresse",
            "password" => array('password' => array('first' => "unMotDePasse", "second" => "unMotDePasse"))
        );

        $object = new \Waldo\SushiBundle\Entity\Utilisateur();
        $object->setId(42)
                ->setUsername($formData['username'])
                ->setEmail($formData['email'])
                ->setPassword($formData['password']['password']['first'])
                ->setAdresse($formData['adresse']);

        $utilisateurType = new \Waldo\SushiBundle\Form\Type\UtilisateurType();
        $form = $this->factory->create($utilisateurType, $object);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        $this->assertArrayNotHasKey("password", $children);
    }

}
