<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Commentaires;


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
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaires", mappedBy="figureId", cascade={"persist"})
     */
    private $commentaires;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $auteur;


    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
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
    

    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setFigureId($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getFigureId() === $this) {
                $commentaire->setFigureId(null);
            }
        }

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }
}
