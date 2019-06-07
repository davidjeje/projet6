<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Entity\Commentaires;
use App\Entity\Tricks;

/**
 * @ORM\Table(name="user")
 * @UniqueEntity(fields="email")
 * @ORM\Entity()
 */
class User implements UserInterface, \Serializable
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=250)
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(name="roles", type="array")
     */
    private $roles = array();

    /**
     * Plusieur utilisateur peuvent créer plusieurs figures
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Tricks", mappedBy="auteur", cascade={"persist"})
     */
    private $trick;

    /**
     * @ORM\Column(name="token", type="string", unique = true)
     */
    private $token;

     
    /**
     * Un utilisateur a potentiellement plusieurs commentaires
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaires", mappedBy="autorId", cascade={"persist"})
     */
    private $commentaireId;

    /**
     * @ORM\Column(type="string", nullable = true)
     *
     * @Assert\Image(
     *     minWidth = 40,
     *     maxWidth = 60,
     *     minHeight = 40,
     *     maxHeight = 60
     * )
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;
     

    public function __construct()
    {
        $this->isActive = false;
        $this->trick = new ArrayCollection();
        $this->token = bin2hex(random_bytes(16));
        $this->commentaire = new ArrayCollection();
        $this->commentaireId = new ArrayCollection();

        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getRoles()
    {
        if (empty($this->roles)) {
            return ['ROLE_USER'];
        }
        return $this->roles;
    }

    public function addRole($role)
    {
        $this->roles[] = $role;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize() 
     */
    public function serialize()
    {
        return serialize(
            array(
            $this->id,
            $this->email,
            $this->password,
            $this->isActive,
                // see section on salt below
                // $this->salt,
            )
        );
    }

    /**
     * @see \Serializable::unserialize() 
     */
    public function unserialize($serialized)
    {
        list(
                $this->id,
                $this->email,
                $this->password,
                $this->isActive,
                // see section on salt below
                // $this->salt
                ) = unserialize($serialized);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Tricks[]
     */
    public function getTrick(): Collection
    {
        return $this->trick;
    }

    public function addTrick(Tricks $trick): self
    {
        if (!$this->trick->contains($trick)) {
            $this->trick[] = $trick;
            $trick->addAuteur($this);
        }

        return $this;
    }

    public function removeTrick(Tricks $trick): self
    {
        if ($this->trick->contains($trick)) {
            $this->trick->removeElement($trick);
            $trick->removeAuteur($this);
        }

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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
            $commentaireId->setAutorId($this);
        }

        return $this;
    }

    public function removeCommentaireId(Commentaires $commentaireId): self
    {
        if ($this->commentaireId->contains($commentaireId)) {
            $this->commentaireId->removeElement($commentaireId);
            // set the owning side to null (unless already changed)
            if ($commentaireId->getAutorId() === $this) {
                $commentaireId->setAutorId(null);
            }
        }

        return $this;
    }
}
