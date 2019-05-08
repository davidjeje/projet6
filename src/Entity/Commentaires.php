<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Paginator;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentairesRepository")
 */
class Commentaires
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $dateCommentaire;

    /**
     * @ORM\Column(type="integer")
     */
    /**
     * Les commentaires sont liés à une figure
     * @ORM\ManyToOne(targetEntity="App\Entity\Tricks", inversedBy="commentairesId", cascade={"persist"})
     * @ORM\JoinColumn(name="figureId", referencedColumnName="id")
     */
    private $figureId;

    /**
     * Plusieurs commentaires peuvent être écrit par un auteur.
     * @ORM\ManyToOne(targetEntity="App\Entity\User",
     inversedBy="commentaireId", cascade={"persist"})
     * @ORM\JoinColumn(name="autorId", referencedColumnName="id")
     */
    private $autorId;

    /**
     * Plusieurs commentaires peuvent être sur une page.
     * @ORM\ManyToOne(targetEntity="App\Entity\Paginator",
     inversedBy="commentaireId", cascade={"persist"})
     * @ORM\JoinColumn(name="paginatorId", referencedColumnName="id")
     */
    private $paginatorId;

    public function __construct()
    {
        $this->autorId = new ArrayCollection();
        $this->paginatorId = new ArrayCollection();

    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getDateCommentaire(): ?string
    {
        return $this->dateCommentaire;
    }

    public function setDateCommentaire(?string $dateCommentaire): self
    {
        $this->dateCommentaire = $dateCommentaire;

        return $this;
    }

    public function getFigureId(): ?Tricks
    {
        return $this->figureId;
    }

    public function setFigureId(?Tricks $figureId): self
    {
        $this->figureId = $figureId;

        return $this;
    }

    public function getAutorId(): ?User
    {
        return $this->autorId;
    }

    public function setAutorId(?User $autorId): self
    {
        $this->autorId = $autorId;

        return $this;
    }

    public function getPaginatorId(): ?Paginator
    {
        return $this->paginatorId;
    }

    public function setPaginatorId(?Paginator $paginatorId): self
    {
        $this->paginatorId = $paginatorId;

        return $this;
    }

    
   

    
    
    
   
}
