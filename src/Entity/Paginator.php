<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;
use App\Entity\Commentaires;
use App\Entity\Tricks;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaginatorRepository")
 */
class Paginator
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="page", type="integer")
     */
    private $page;

    /**
     * @ORM\Column(name="nbPages", type="integer")
     */
    private $nbPages;

    /**
     * @ORM\Column(name ="nomRoute", type="string")
     */
    private $nomRoute;

    /**
     * @ORM\Column(name ="paramsRoute", type="array", nullable=true)
     */
    private $paramsRoute = array();

    /**
     * Une page a potentiellement plusieurs commentaires
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaires", mappedBy="paginatorId", cascade={"persist"})
     */
    private $commentaireId;

    public function __construct()
    {
        $this->commentaireId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getNbPages(): ?int
    {
        return $this->nbPages;
    }

    public function setNbPages(int $nbPages): self
    {
        $this->nbPages = $nbPages;

        return $this;
    }

    public function getNomRoute(): ?string
    {
        return $this->nomRoute;
    }

    public function setNomRoute(string $nomRoute): self
    {
        $this->nomRoute = $nomRoute;

        return $this;
    }

    public function getParamsRoute(): ?array
    {
        return $this->paramsRoute;
    }

    public function setParamsRoute(?array $paramsRoute): self
    {
        $this->paramsRoute = $paramsRoute;

        return $this;
    }

    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentaireId(): Collection
    {
        return $this->commentaireId;
    }

    public function addCommentaireId(Commentaires $commentaireId): self
    {
        if (!$this->commentaireId->contains($commentaireId)) {
            $this->commentaireId[] = $commentaireId;
            $commentaireId->setPaginatorId($this);
        }

        return $this;
    }

    public function removeCommentaireId(Commentaires $commentaireId): self
    {
        if ($this->commentaireId->contains($commentaireId)) {
            $this->commentaireId->removeElement($commentaireId);
            // set the owning side to null (unless already changed)
            if ($commentaireId->getPaginatorId() === $this) {
                $commentaireId->setPaginatorId(null);
            }
        }

        return $this;
    }
}
