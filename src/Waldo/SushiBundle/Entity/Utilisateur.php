<?php

namespace Waldo\SushiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ici nous implementons UserInterface et \Serializable conformément à la
 * documentation http://symfony.com/doc/current/cookbook/security/entity_provider.html
 * Cela permet d'avoir une entité qui peut servir avec le système de sécurité
 *
 * Grâce au Assert, nous pouvons implémenté ici des contrainte de validation.
 *
 * @ORM\Entity(repositoryClass="Waldo\SushiBundle\EntityRepository\UtilisateurRepository")
 * @ORM\Table(name="utilisateur")
 */
class Utilisateur implements UserInterface, \Serializable
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", nullable=false, unique=true)
     */
    protected $id;

    /**
     * @ORM\Column(name="username", type="string", length=50, nullable=false, unique=true)
     * @Assert\NotBlank(message="Un nom d'utilisateur doit être défini.")
     * @Assert\Length(
     *      min = "6",
     *      minMessage = "Votre nom d'utilisateur doit être d'au moins {{ limit }} characteres."
     * )
     * @Assert\Regex(
     *     pattern="/[a-z0-9\.\-]/",
     *     match=true,
     *     message="Votre nom d'utilisateur peut uniquement comporter des caractères Alphanumérique, '.' et '-'"
     * )
     *
     * @var string $username login de l'utilisateur
     */
    protected $username;

    /**
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     * @Assert\NotBlank(
     *      message="Un mot de passe doit être défini.",
     *      groups={"registration"}
     * )
     * @Assert\Length(
     *      min = "8",
     *      minMessage = "Votre mot de passe doit être d'au moins {{ limit }} characteres.",
     *      groups={"registration"}
     * )
     *
     * @var string $password mot de passe de l'utilisateur
     */
    protected $password;

    /**
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     *
     * @var string $salt grain de sel, pour épicer le mot de passe
     */
    protected $salt;

    /**
     * @ORM\Column(name="email", type="string", length=200, nullable=false, unique=true)
     * @Assert\NotBlank(message="Un nom doit être défini.")
     * @Assert\Email(
     *     message = "L'email saisie : '{{ value }}', n'est pas un email valide.",
     *     checkMX = false
     * )
     * @var string $email email de l'utilisateur
     */
    protected $email;

    /**
     * @ORM\Column(name="roles", type="array", nullable=true)
     *
     * @var string $roles rôles attribué à l'utilisateur
     */
    protected $roles;

    /**
     * @ORM\Column(name="adresse", type="text", nullable=true)
     * 
     * @var string $adresse adresse de l'utilisateur
     */
    protected $adresse;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getAdresse()
    {
        return $this->adresse;
    }

    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array($this->id));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list($this->id) = unserialize($serialized);
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {

    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

}
