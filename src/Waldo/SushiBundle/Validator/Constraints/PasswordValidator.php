<?php

namespace Waldo\SushiBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Cette class met en oeuvre un validateur de mot de passe.
 * celui-ci est couplé avec Waldo\SushiBundle\Validator\Constraints\Password qui
 * contien les paramètrages ainsi que les différents messages d'erreurs.
 *
 * plus de détail : http://symfony.com/doc/current/cookbook/validation/custom_constraint.html
 *
 * @author Valérian Girard <valerian.girard@educagri.fr>
 */
class PasswordValidator extends ConstraintValidator
{
    /**
     * Le paramètre $value est la valeur du champs qui a été tagué avec l'annotation
     * définit par le nom de la class (Password)
     *
     * @param Mix $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {       
        $violations = array();

        $nbUpperCaseCharacter = preg_match_all("/(?<uperCaseLetter>[A-Z])/", $value);

        if($constraint->minUperCase > $nbUpperCaseCharacter) {
            $violations[] = array(
                    "message" => $constraint->messageMinUperCase,
                    "params" => array('%minUperCase%' => $constraint->minNumber),
                    "invalidValue" => null,
                    "pluralization" => $constraint->minUperCase
                    );
        } elseif($constraint->maxUperCase < $nbUpperCaseCharacter) {
            $violations[] = array(
                    "message" => $constraint->messageMaxUperCase,
                    "params" => array('%maxUperCase%' => $constraint->maxNumber),
                    "invalidValue" => null,
                    "pluralization" => $constraint->maxUperCase
            );
        }


        $nbNumber = preg_match_all("/(?<number>[0-9])/", $value);

        if($constraint->minNumber > $nbNumber) {
            $violations[] = array(
                    "message" => $constraint->messageMinNumber,
                    "params" => array('%minNumber%' => $constraint->minNumber),
                    "invalidValue" => null,
                    "pluralization" => $constraint->minNumber
            );
        } elseif($constraint->maxNumber < $nbNumber) {
            $violations[] = array(
                    "message" => $constraint->messageMaxNumber,
                    "params" => array('%maxNumber%' => $constraint->maxNumber),
                    "invalidValue" => null,
                    "pluralization" => $constraint->maxNumber
            );
        }


        preg_match_all("/(?<sc>[^A-Za-z0-9])/", $value, $matchesSpecialCharactere);
        $specialCaractereAllowedList = str_split($constraint->specialCharacteresAllowed);

        $nbSpecialCharactere = 0;
        foreach ($matchesSpecialCharactere['sc'] as $specialChar) {
            if(in_array($specialChar, $specialCaractereAllowedList)) {
                $nbSpecialCharactere++;
            }
        }

        if($constraint->minSpecialCharactere > $nbSpecialCharactere) {
            $violations[] = array(
                    "message" => $constraint->messageMinSpecialCharactere,
                    "params" => array('%minSpecialCharactere%' => $constraint->minNumber, "%specialCharacteresAllowed%" => $constraint->specialCharacteresAllowed),
                    "invalidValue" => null,
                    "pluralization" => $constraint->minSpecialCharactere
            );
        } elseif($constraint->maxSpecialCharactere < $nbSpecialCharactere) {

            $violations[] = array(
                    "message" => $constraint->messageMaxSpecialCharactere,
                    "params" => array('%maxSpecialCharactere%' => $constraint->maxNumber, "%specialCharacteresAllowed%" => $constraint->specialCharacteresAllowed),
                    "invalidValue" => null,
                    "pluralization" => $constraint->maxSpecialCharactere
            );
        }
       

        if(0 < count($violations)) {
            $this->context->addViolation($constraint->message);
            
            foreach($violations as $violation) {
                extract($violation);
                $this->context->addViolation($message, $params, $invalidValue, $pluralization);
            }
        }
        

    }

}
