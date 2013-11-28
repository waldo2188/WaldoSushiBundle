<?php

namespace Waldo\SushiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Ce type de formulaire présente trois choses.
 * - l'utilisation des évènements (FormEvents::POST_SET_DATA) pour modiffier un
 * formulaire en fonction de paramètre(s) extérieur(s)
 *
 * - l'utilisation d'un second type de formulaire (utilisateur_password) et la
 * validation en cascade.
 *
 * - l'utilisation de groupe de validation en fonction de paramètre(s) extérieur(s)
 */
class UtilisateurType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('username')
                ->add('email', 'email')
                ->add('adresse', 'textarea', array("required" => false))
        ;

        // Pour en savoir plus sur la modification dynamique de formulaire
        // http://symfony.com/doc/current/cookbook/form/dynamic_form_modification.html
        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) use ($options) {
            if(!$event->getData() || !$event->getData()->getId()) {
                $event->getForm()->add('password',  'utilisateur_password');
            }
        });
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            // Permet de relier ce type de formulaire à une entité
            // Et donc de récupérer cette entité
            'data_class' => 'Waldo\SushiBundle\Entity\Utilisateur',

            // Dans le cas ou l'utilisateur est nouveau, on utilise le groupe
            // de validation "registrations".
            'validation_groups' => function(FormInterface $form) {
                if($form->getData()->getId() === null) {
                    return array("registration");
                }
            }
        ));
    }

    public function getName()
    {
        return 'utilisateur';
    }

}