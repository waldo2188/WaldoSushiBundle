<?php

namespace Waldo\SushiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Waldo\SushiBundle\EntityRepository\SushiRepository")
 * @ORM\Table(name="sushi")
 */
class Sushi
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", nullable=false, unique=true)
     */
    protected $id;

    /**
     * @ORM\Column(name="nom", type="string", length=50, nullable=true)
     *
     * @var string $nom nom du sushi
     */
    protected $nom;

    /**
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     *
     * @var string $description description du sushi
     */
    protected $description;

    /**
     * @ORM\Column(name="creationDate", type="date", nullable=true)
     *
     * @var date $creationDate Date de création
     */
    protected $creationDate;

    /**
     * @ORM\Column(name="updateDate", type="date", nullable=true)
     *
     * @var date $updateDate Date de dernière modification
     */
    protected $updateDate;


    function __construct($nom = null, $description = null)
    {
        $this->nom = $nom;
        $this->description = $description;
    }


    /**
     * Cette méthode est appelé avant que l'objet ne soit persisté, s'il
     * n'esxiste pas encore dans la base de données
     *
     * @ORM\PrePersist
     */
    public function doStuffOnPrePersist()
    {
        if ($this->creationDate == null) {
            $this->creationDate = new \DateTime("now");
        }
    }

    /**
     * Cette méthode est appelé avant que l'objet ne soit persisté s'il esiste
     * déjà dans la base de données
     *
     * @ORM\PreUpdate
     */
    public function doStuffOnPreUpdate()
    {
        $this->updateDate = new \DateTime("now");
    }


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate(date $creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    public function setUpdateDate(date $updateDate)
    {
        $this->updateDate = $updateDate;
    }

}