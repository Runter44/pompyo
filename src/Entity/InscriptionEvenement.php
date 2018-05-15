<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InscriptionEvenementRepository")
 */
class InscriptionEvenement
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="inscriptionEvenements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Evenement", inversedBy="inscriptionEvenements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $evenement;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank(message="Vous devez entrer un nombre valide")
     * @Assert\Range(
     *      min = 1,
     *      max = 20,
     *      minMessage = "Il doit obligatoirement y avoir au moins un adulte présent",
     *      maxMessage = "Vous ne pouvez pas inscrire plus de 20 adultes à la fois"
     * )
     */
    private $nbAdultes;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank(message="Vous devez entrer un nombre valide")
     * @Assert\Range(
     *      min = 0,
     *      max = 20,
     *      minMessage = "Cette valeur ne peut pas être négative",
     *      maxMessage = "Vous ne pouvez pas inscrire plus de 20 enfants à la fois"
     * )
     */
    private $nbEnfants;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank(message="Vous devez entrer un commentaire")
     * @Assert\Length(max=500, maxMessage="Le commentaire ne peut pas dépasser 500 caractères")
     */
    private $commentaires;

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }

    public function getNbAdultes(): ?int
    {
        return $this->nbAdultes;
    }

    public function setNbAdultes(int $nbAdultes): self
    {
        $this->nbAdultes = $nbAdultes;

        return $this;
    }

    public function getNbEnfants(): ?int
    {
        return $this->nbEnfants;
    }

    public function setNbEnfants(int $nbEnfants): self
    {
        $this->nbEnfants = $nbEnfants;

        return $this;
    }

    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }

    public function setCommentaires(string $commentaires): self
    {
        $this->commentaires = $commentaires;

        return $this;
    }
}
