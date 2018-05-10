<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Le prénom est invalide")
     * @Assert\Length(max=255, maxMessage="Le prénom est invalide")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Le nom est invalide")
     * @Assert\Length(max=255, maxMessage="Le nom est invalide")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="L'adresse e-mail est invalide")
     * @Assert\Length(max=255, maxMessage="L'adresse e-mail est invalide")
     * @Assert\Email(message="L'adresse e-mail est invalide", checkMX = true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Length(max=255, maxMessage="Le numéro de téléphone est invalide")
     * @Assert\Regex(pattern="/^(0|\\+33)[1-9][0-9]{8}$/", message="Le numéro de téléphone est invalide")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="L'objet est invalide")
     * @Assert\Length(max=255, maxMessage="L'objet est invalide")
     */
    private $object;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank(message="Le message est invalide")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEnvoi;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ipEnvoi;

    /**
     * @ORM\Column(type="boolean")
     */
    private $envoye;

    public function getId()
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(string $object): self
    {
        $this->object = $object;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDateEnvoi(): ?\DateTimeInterface
    {
        return $this->dateEnvoi;
    }

    public function setDateEnvoi(\DateTimeInterface $dateEnvoi): self
    {
        $this->dateEnvoi = $dateEnvoi;

        return $this;
    }

    public function getIpEnvoi(): ?string
    {
        return $this->ipEnvoi;
    }

    public function setIpEnvoi(string $ipEnvoi): self
    {
        $this->ipEnvoi = $ipEnvoi;

        return $this;
    }

    public function getEnvoye(): ?bool
    {
        return $this->envoye;
    }

    public function setEnvoye(bool $envoye): self
    {
        $this->envoye = $envoye;

        return $this;
    }
}
