<?php

namespace Waldo\SushiBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * Le tag Annotation est nécessaire pour utiliser cette contrainte avec des annotations
 */
class Password extends Constraint
{
    public $minNumber = 2;
    public $maxNumber = 999;
    public $minUperCase = 2;
    public $maxUperCase = 999;
    public $minSpecialCharactere = 2;
    public $maxSpecialCharactere = 999;
    public $specialCharacteresAllowed = "!#$%&()+,-./:;<=>?@[\]^_{|}~";
    public $message = 'Votre mot de passe ne correspond pas à la politique au site. Il doit contenir :';
    public $messageMinNumber = 'au moins un chiffre|au moins %minNumber% chiffres';
    public $messageMaxNumber = 'au maximum un chiffre|au maximum %maxNumber% chiffres';
    public $messageMinUperCase = 'au moins un caractère en Majuscule|au moins %minUperCase% caractères en Majuscule';
    public $messageMaxUperCase = 'au maximum un caractère en Majuscule|au maximum %maxUperCase% caractères en Majuscule';
    public $messageMinSpecialCharactere = 'au moins un caractère spéciale (%specialCharacteresAllowed%)|au moins %minSpecialCharactere% caractères spéciaux (%specialCharacteresAllowed%)';
    public $messageMaxSpecialCharactere = 'au maximum un caractère spéciale (%specialCharacteresAllowed%)|au maximum %maxSpecialCharactere% caractères spéciaux (%specialCharacteresAllowed%)';

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
}
