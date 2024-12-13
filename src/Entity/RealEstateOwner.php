<?php

namespace App\Entity;

use App\Repository\RealEstateOwnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RealEstateOwnerRepository::class)]
class RealEstateOwner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    

    #[ORM\ManyToOne(inversedBy: 'realEstateOwners')]
    private ?City $city = null;

    #[ORM\Column(length: 40)]
    private ?string $ownerName = null;

    #[ORM\Column(length: 10)]
    private ?string $ownerPhoneNumber = null;

    #[ORM\Column(length: 40)]
    private ?string $ownerEmail = null;

    #[ORM\OneToOne(inversedBy: 'realEstateOwner', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?WebUser $webUser = null;

    /**
     * @var Collection<int, RealEstate>
     */
    #[ORM\OneToMany(targetEntity: RealEstate::class, mappedBy: 'owner')]
    private Collection $realEstates;

    public function __construct()
    {
        $this->realEstates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebId(): ?WebUser
    {
        return $this->webId;
    }

    public function setWebId(?WebUser $webId): static
    {
        $this->webId = $webId;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getOwnerName(): ?string
    {
        return $this->ownerName;
    }

    public function setOwnerName(string $ownerName): static
    {
        $this->ownerName = $ownerName;

        return $this;
    }

    public function getOwnerPhoneNumber(): ?string
    {
        return $this->ownerPhoneNumber;
    }

    public function setOwnerPhoneNumber(string $ownerPhoneNumber): static
    {
        $this->ownerPhoneNumber = $ownerPhoneNumber;

        return $this;
    }

    public function getOwnerEmail(): ?string
    {
        return $this->ownerEmail;
    }

    public function setOwnerEmail(string $ownerEmail): static
    {
        $this->ownerEmail = $ownerEmail;

        return $this;
    }

    public function getWebUser(): ?WebUser
    {
        return $this->webUser;
    }

    public function setWebUser(WebUser $webUser): static
    {
        $this->webUser = $webUser;

        return $this;
    }

    /**
     * @return Collection<int, RealEstate>
     */
    public function getRealEstates(): Collection
    {
        return $this->realEstates;
    }

    public function addRealEstate(RealEstate $realEstate): static
    {
        if (!$this->realEstates->contains($realEstate)) {
            $this->realEstates->add($realEstate);
            $realEstate->setOwner($this);
        }

        return $this;
    }

    public function removeRealEstate(RealEstate $realEstate): static
    {
        if ($this->realEstates->removeElement($realEstate)) {
            // set the owning side to null (unless already changed)
            if ($realEstate->getOwner() === $this) {
                $realEstate->setOwner(null);
            }
        }

        return $this;
    }
}
