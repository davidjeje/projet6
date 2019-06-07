<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Commentaires;
use App\Entity\User;

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
     * @ORM\Column(type="string", length=255, unique = true)
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
     * @Assert\Image(
     *     minWidth = 400,
     *     maxWidth = 900,
     *     minHeight = 400,
     *     maxHeight = 857
     * )
     */
    private $image;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\Image(
     *     minWidth = 400,
     *     maxWidth = 900,
     *     minHeight = 400,
     *     maxHeight = 857
     * )
     */
    private $secondeImage;

    /**
     * @ORM\Column(type="string")
     */
    private $DateCreation;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $DateModification;
   
    /**
      * Une figure a potentiellement plusieurs commentaires
      * @ORM\OneToMany(targetEntity="App\Entity\Commentaires", mappedBy="figureId", cascade={"persist"})
      */
    private $commentairesId;
    
    /**
    * Plusieur figures peut être créées par plusieur user
    * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="trick", cascade={"persist"})
    * @ORM\JoinColumn(name="auteurId", referencedColumnName="id")
    */
    private $auteur;

    /**
     * @ORM\Column(type="string")
     *
     *
     */
    private $video;

    /**
     * @ORM\Column(type="string")
     *
     *
     */
    private $secondeVideo;

    /**
     * @ORM\Column(type="string")
     *
     *
     */
    private $troisiemeVideo;

    /**
     * @ORM\Column(type="string")
     *
     *
     */
    private $slug;


    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->auteur = new ArrayCollection();
        $this->commentairesId = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getAuteur(): Collection
    {
        return $this->auteur;
    }

    public function addAuteur(User $auteur): self
    {
        if (!$this->auteur->contains($auteur)) {
            $this->auteur[] = $auteur;
        }
        return $this;
    }

    public function removeAuteur(User $auteur): self
    {
        if ($this->auteur->contains($auteur)) {
            $this->auteur->removeElement($auteur);
        }
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): self
    {
        $this->video = $video;

        return $this;
    }

    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentairesId(): Collection
    {
        return $this->commentairesId;
    }

    public function addCommentairesId(Commentaires $commentairesId): self
    {
        if (!$this->commentairesId->contains($commentairesId)) {
            $this->commentairesId[] = $commentairesId;
            $commentairesId->setFigureId($this);
        }

        return $this;
    }

    public function removeCommentairesId(Commentaires $commentairesId): self
    {
        if ($this->commentairesId->contains($commentairesId)) {
            $this->commentairesId->removeElement($commentairesId);
            // set the owning side to null (unless already changed)
            if ($commentairesId->getFigureId() === $this) {
                $commentairesId->setFigureId(null);
            }
        }

        return $this;
    }

    public function getSecondeImage()
    {
        return $this->secondeImage;
    }

    public function setSecondeImage($secondeImage): self
    {
        $this->secondeImage = $secondeImage;

        return $this;
    }

    public function getSecondeVideo(): ?string
    {
        return $this->secondeVideo;
    }

    public function setSecondeVideo(string $secondeVideo): self
    {
        $this->secondeVideo = $secondeVideo;

        return $this;
    }

    public function getTroisiemeVideo(): ?string
    {
        return $this->troisiemeVideo;
    }

    public function setTroisiemeVideo(string $troisiemeVideo): self
    {
        $this->troisiemeVideo = $troisiemeVideo;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
