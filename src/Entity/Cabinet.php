<?php

namespace App\Entity;

use App\Repository\CabinetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CabinetRepository::class)]
class Cabinet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $zipcode = null;

    #[ORM\Column(length: 255)]
    private ?string $contactEmail = null;

    #[ORM\Column]
    private ?string $numBCE = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }
    
    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }
    
    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }
    
    public function setZipcode(string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }
    

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(string $contactEmail): static
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    public function getNumBCE(): ?string
    {
        return $this->numBCE;
    }

    public function setNumBCE(string $numBCE): static
    {
        $this->numBCE = $numBCE;

        return $this;
    }
}
