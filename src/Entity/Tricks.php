<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TricksRepository")
 */
class Tricks
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $groupname;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Ajouter une image au format jpeg ou png.")
     * @Assert\File(mimeTypes={ "image/jpeg","image/png", "image/jpg, images/jpeg","images/png", "images/jpg" })
     */
    private $image;

    /**
     * @ORM\Column(type="string")
     */
    private $DateCreation;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
   private $DateModification;
   /**
     * Un client a potentiellement plusieurs adresses
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaire", mappedBy="figureId")
     */
    private $commentaire;

    public function __construct() 
    {
        $this->commentaire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getGroupname(): ?string
    {
        return $this->groupname;
    }

    public function setGroupname(?string $groupname): self
    {
        $this->groupname = $groupname;

        return $this;
    }

    public function getImage() 
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getDateCreation() 
    {
        return $this->DateCreation;
    }

    public function setDateCreation($DateCreation)
    {
        $this->DateCreation = $DateCreation;

        return $this;
    }

    public function getDateModification() 
    {
        return $this->DateModification;
    }

    public function setDateModification($DateModification)
    {
        $this->DateModification = $DateModification;

        return $this;
    }
    public function getcommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setcommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
