<?php

namespace App\Entity;

use App\Utils\PasswordGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 * @UniqueEntity("email")
 */
class Utilisateur implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     *
     * @Assert\Length(max=100, maxMessage="L'adresse mail est trop longue")
     * @Assert\NotBlank(message="L'adresse e-mail est invalide")
     * @Assert\Email(message="L'adresse e-mail est invalide", checkMX = true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\Length(max=50, maxMessage="Le prénom est trop long")
     * @Assert\NotBlank(message="Le prénom est invalide")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\Length(max=50, maxMessage="Le nom est trop long")
     * @Assert\NotBlank(message="Le nom est invalide")
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateInscription;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InscriptionEvenement", mappedBy="utilisateur")
     */
    private $inscriptionEvenements;

    public function __construct()
    {
        $this->inscriptionEvenements = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): self
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return array($this->getRole());
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return Collection|InscriptionEvenement[]
     */
    public function getInscriptionEvenements(): Collection
    {
        return $this->inscriptionEvenements;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvenementsInscrit()
    {
        $evenements = array();
        foreach ($this->inscriptionEvenements as $inscriptionEvenement) {
            array_push($evenements, $inscriptionEvenement->getEvenement());
        }
        return $evenements;
    }

    public function addInscriptionEvenement(InscriptionEvenement $inscriptionEvenement): self
    {
        if (!$this->inscriptionEvenements->contains($inscriptionEvenement)) {
            $this->inscriptionEvenements[] = $inscriptionEvenement;
            $inscriptionEvenement->setUtilisateur($this);
        }

        return $this;
    }

    public function removeInscriptionEvenement(InscriptionEvenement $inscriptionEvenement): self
    {
        if ($this->inscriptionEvenements->contains($inscriptionEvenement)) {
            $this->inscriptionEvenements->removeElement($inscriptionEvenement);
            // set the owning side to null (unless already changed)
            if ($inscriptionEvenement->getUtilisateur() === $this) {
                $inscriptionEvenement->setUtilisateur(null);
            }
        }

        return $this;
    }
}
