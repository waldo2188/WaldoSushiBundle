<?php

namespace Waldo\SushiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Ceci est un type de formulaire.
 * Il correspond à ce qui est affiché à l'utilisateur pour qu'il puisse saisir
 * de nouveau sushi.
 *
 * La valeur de l'option data_class permet de définir au framework que ce type
 * est lié à la class Waldo\SushiBundle\Entity\Sushi.
 * Donc lorsque nous récupèrerons le résultat du formulaire, celui-ci sera une
 * instance de la class Sushi.
 *
 * La valeur de getName est lié au service définit dans `Waldo/SushiBundle/
 * Resources/config/Form.xml`
 * Cela permet de définir ce type de formulaire comme un service.
 */
class SushiType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('nom')
                ->add('description', 'textarea')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Waldo\SushiBundle\Entity\Sushi'
        ));
    }

    public function getName()
    {
        return 'waldo_sushibundle_sushitype';
    }

}