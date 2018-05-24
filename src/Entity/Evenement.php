<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EvenementRepository")
 * @UniqueEntity("nom")
 */
class Evenement
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
     * @Assert\NotBlank(message="Le nom est invalide")
     * @Assert\Length(max=100, maxMessage="Le nom est trop long")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Le lieu est invalide")
     * @Assert\Length(max=255, maxMessage="Le lieu est trop long")
     */
    private $lieu;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank(message="Le résumé est invalide")
     * @Assert\Length(max=400, maxMessage="Le résumé est trop long")
     */
    private $teaser;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank(message="Vous devez entrer une description")
     * @Assert\Length(min=100, minMessage="La description doit au moins faire 100 caractères.")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $inscriptionPossible;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @Assert\Date(message="La date limite est invalide")
     */
    private $dateLimiteInscription;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visiblePublic;

    /**
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank(message="La date de début est invalide")
     * @Assert\Date(message="La date de début est invalide")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="time")
     *
     * @Assert\NotBlank(message="La date de début est invalide")
     * @Assert\Time(message="La date de début est invalide")
     */
    private $heureDebut;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roleMinimum;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InscriptionEvenement", mappedBy="evenement")
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getTeaser(): ?string
    {
        return $this->teaser;
    }

    public function setTeaser(string $teaser): self
    {
        $this->teaser = $teaser;

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

    public function getInscriptionPossible(): ?bool
    {
        return $this->inscriptionPossible;
    }

    public function setInscriptionPossible(bool $inscriptionPossible): self
    {
        $this->inscriptionPossible = $inscriptionPossible;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(?\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getVisiblePublic(): ?bool
    {
        return $this->visiblePublic;
    }

    public function setVisiblePublic(bool $visiblePublic): self
    {
        $this->visiblePublic = $visiblePublic;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(?\DateTimeInterface $heureDebut): self
    {
        $this->heureDebut = $heureDebut;

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

    public function getRoleMinimum(): ?string
    {
        return $this->roleMinimum;
    }

    public function setRoleMinimum(string $roleMinimum): self
    {
        $this->roleMinimum = $roleMinimum;

        return $this;
    }

    /**
     * @return Collection|InscriptionEvenement[]
     */
    public function getInscriptionEvenements(): Collection
    {
        return $this->inscriptionEvenements;
    }

    public function addInscriptionEvenement(InscriptionEvenement $inscriptionEvenement): self
    {
        if (!$this->inscriptionEvenements->contains($inscriptionEvenement)) {
            $this->inscriptionEvenements[] = $inscriptionEvenement;
            $inscriptionEvenement->setEvenement($this);
        }

        return $this;
    }

    public function removeInscriptionEvenement(InscriptionEvenement $inscriptionEvenement): self
    {
        if ($this->inscriptionEvenements->contains($inscriptionEvenement)) {
            $this->inscriptionEvenements->removeElement($inscriptionEvenement);
            // set the owning side to null (unless already changed)
            if ($inscriptionEvenement->getEvenement() === $this) {
                $inscriptionEvenement->setEvenement(null);
            }
        }

        return $this;
    }
}
