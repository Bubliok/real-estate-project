<?php

namespace App\Entity;

use App\Repository\WebUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebUserRepository::class)]
class WebUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $Username = null;

    #[ORM\Column(length: 50)]
    private ?string $Password = null;

    #[ORM\Column]
    private ?bool $isAdmin = false;

    #[ORM\OneToOne(mappedBy: 'webUser', cascade: ['persist', 'remove'])]
    private ?RealEstateOwner $realEstateOwner = null;

    #[ORM\OneToOne(mappedBy: 'WebUser', cascade: ['persist', 'remove'])]
    private ?RealEstateAgent $realEstateAgent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(string $Username): static
    {
        $this->Username = $Username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): static
    {
        $this->Password = $Password;

        return $this;
    }

    public function isAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setAdmin(bool $isAdmin): static
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getRealEstateOwner(): ?RealEstateOwner
    {
        return $this->realEstateOwner;
    }

    public function setRealEstateOwner(RealEstateOwner $realEstateOwner): static
    {
        // set the owning side of the relation if necessary
        if ($realEstateOwner->getWebUser() !== $this) {
            $realEstateOwner->setWebUser($this);
        }

        $this->realEstateOwner = $realEstateOwner;

        return $this;
    }

    public function getRealEstateAgent(): ?RealEstateAgent
    {
        return $this->realEstateAgent;
    }

    public function setRealEstateAgent(RealEstateAgent $realEstateAgent): static
    {
        // set the owning side of the relation if necessary
        if ($realEstateAgent->getWebUser() !== $this) {
            $realEstateAgent->setWebUser($this);
        }

        $this->realEstateAgent = $realEstateAgent;

        return $this;
    }
}
