<?php

namespace Waldo\SushiBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Waldo\SushiBundle\Entity\Utilisateur;

/**
 * Description of UtilisateurService
 *
 * @author waldo
 */
class UtilisateurService
{

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface
     */
    protected $encodeurFactory;

    function __construct(EntityManager $em, EncoderFactoryInterface $encodeurFactory)
    {
        $this->em = $em;
        $this->encodeurFactory = $encodeurFactory;
    }

    /**
     * Cette méthode agit en fonction de l'état de l'objet Utilisateur
     * - si c'est un nouvel utilisateur il le crée
     * - si c'est un utilisateur existant il fait autre chose
     *
     * @param \Waldo\SushiBundle\Entity\Utilisateur $utilisateur
     */
    public function manageUtilisateur(Utilisateur $utilisateur)
    {
        if($utilisateur->getId() == null) {
            $this->createUtilisateur($utilisateur);
        }

        $this->em->persist($utilisateur);
        $this->em->flush();

        return $utilisateur;
    }

    /**
     * Crée un utilisateur qui n'est pas encore dans la base de données
     * - Génère un grain de sel
     * - hash le mot de passe
     * - sauvegarde l'utilisateur
     *
     * @param \Waldo\SushiBundle\Entity\Utilisateur $utilisateur
     */
    public function createUtilisateur(Utilisateur $utilisateur)
    {
        $encodeur = $this->encodeurFactory->getEncoder($utilisateur);

        $password = $utilisateur->getPassword();

        $utilisateur->setSalt($this->saltGenerator());

        $utilisateur->setPassword($encodeur->encodePassword($password, $utilisateur->getSalt()));

    }

    public function saltGenerator()
    {
        return base64_encode(hash('whirlpool', uniqid(mt_rand(), true)));
    }

}
