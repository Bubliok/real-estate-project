<?php

namespace App\Entity;

use App\Repository\RealEstateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RealEstateRepository::class)]
class RealEstate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $estateName = null;

    #[ORM\Column]
    private ?float $estateArea = null;

    #[ORM\Column]
    private ?int $estateFloor = null;

    #[ORM\Column]
    private ?bool $isFurnished = null;

    #[ORM\Column]
    private ?float $estatePrice = null;

    #[ORM\Column(length: 50)]
    private ?string $estateAddress = null;

    /**
     * @var Collection<int, RealEstateImages>
     */
    #[ORM\OneToMany(targetEntity: RealEstateImages::class, mappedBy: 'RealEstate')]
    private Collection $realEstateImages;

    #[ORM\ManyToOne(inversedBy: 'realEstates')]
    private ?RealEstateType $type = null;

    #[ORM\ManyToOne(inversedBy: 'realEstates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    #[ORM\ManyToOne(inversedBy: 'realEstates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Neighborhood $neighborhood = null;

    #[ORM\ManyToOne(inversedBy: 'realEstates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RealEstateAgent $agent = null;

    #[ORM\ManyToOne(inversedBy: 'realEstates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RealEstateOwner $owner = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateBuiltAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateAddedAt = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    public function __construct()
    {
        $this->realEstateImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEstateName(): ?string
    {
        return $this->estateName;
    }

    public function setEstateName(string $estateName): static
    {
        $this->estateName = $estateName;

        return $this;
    }

    public function getEstateArea(): ?float
    {
        return $this->estateArea;
    }

    public function setEstateArea(float $estateArea): static
    {
        $this->estateArea = $estateArea;

        return $this;
    }

    public function getEstateType(): ?string
    {
        return $this->estateType;
    }

    public function setEstateType(string $estateType): static
    {
        $this->estateType = $estateType;

        return $this;
    }

    public function getEstateFloor(): ?int
    {
        return $this->estateFloor;
    }

    public function setEstateFloor(int $estateFloor): static
    {
        $this->estateFloor = $estateFloor;

        return $this;
    }

    public function isFurnished(): ?bool
    {
        return $this->isFurnished;
    }

    public function setFurnished(bool $isFurnished): static
    {
        $this->isFurnished = $isFurnished;

        return $this;
    }

    public function getEstatePrice(): ?float
    {
        return $this->estatePrice;
    }

    public function setEstatePrice(float $estatePrice): static
    {
        $this->estatePrice = $estatePrice;

        return $this;
    }

    public function getEstateAddress(): ?string
    {
        return $this->estateAddress;
    }

    public function setEstateAddress(string $estateAddress): static
    {
        $this->estateAddress = $estateAddress;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getNeighborhood(): ?string
    {
        return $this->neighborhood;
    }

    public function setNeighborhood(string $neighborhood): static
    {
        $this->neighborhood = $neighborhood;

        return $this;
    }

    /**
     * @return Collection<int, RealEstateImages>
     */
    public function getRealEstateImages(): Collection
    {
        return $this->realEstateImages;
    }

    public function addRealEstateImage(RealEstateImages $realEstateImage): static
    {
        if (!$this->realEstateImages->contains($realEstateImage)) {
            $this->realEstateImages->add($realEstateImage);
            $realEstateImage->setRealEstate($this);
        }

        return $this;
    }

    public function removeRealEstateImage(RealEstateImages $realEstateImage): static
    {
        if ($this->realEstateImages->removeElement($realEstateImage)) {
            // set the owning side to null (unless already changed)
            if ($realEstateImage->getRealEstate() === $this) {
                $realEstateImage->setRealEstate(null);
            }
        }

        return $this;
    }

    public function getType(): ?RealEstateType
    {
        return $this->type;
    }

    public function setType(?RealEstateType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAgent(): ?RealEstateAgent
    {
        return $this->agent;
    }

    public function setAgent(?RealEstateAgent $agent): static
    {
        $this->agent = $agent;

        return $this;
    }

    public function getOwner(): ?RealEstateOwner
    {
        return $this->owner;
    }

    public function setOwner(?RealEstateOwner $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getDateBuiltAt(): ?\DateTimeImmutable
    {
        return $this->dateBuiltAt;
    }

    public function setDateBuiltAt(\DateTimeImmutable $dateBuiltAt): static
    {
        $this->dateBuiltAt = $dateBuiltAt;

        return $this;
    }

    public function getDateAddedAt(): ?\DateTimeImmutable
    {
        return $this->dateAddedAt;
    }

    public function setDateAddedAt(\DateTimeImmutable $dateAddedAt): static
    {
        $this->dateAddedAt = $dateAddedAt;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }
}
