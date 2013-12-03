<?php

namespace Waldo\SushiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class MotDePasseType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('password',  'repeated', array(
                        'type' => 'password',
                        'invalid_message' => 'Les deux mot de passe doivent être identique.',
                        'options' => array('attr' => array('class' => 'password-field')),
                        'required' => true,
                        'first_options'  => array('label' => 'Mot de passe'),
                        'second_options' => array('label' => 'Re-saisir votre mot de passe'),
                    ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Waldo\SushiBundle\Entity\Utilisateur',
            'inherit_data' => true // http://symfony.com/doc/current/cookbook/form/inherit_data_option.html
        ));
    }

    public function getName()
    {
        return 'utilisateur_password';
    }

}