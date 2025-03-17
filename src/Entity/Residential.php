<?php

namespace App\Entity;

use App\Enum\ListingTypeEnum;
use App\Enum\ResidentialBuildTypesEnum;
use App\Enum\ResidentialTypesEnum;
use App\Repository\ResidentialRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResidentialRepository::class)]
class Residential
{
    #[ORM\Id]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Property $propertyId = null;

    #[ORM\Column(type: 'string', enumType: ListingTypeEnum::class)]
    private ResidentialTypesEnum $residentialType;

    #[ORM\Column]
    private ?int $rooms = null;

    #[ORM\Column]
    private ?int $bedrooms = null;

    #[ORM\Column]
    private ?int $bathrooms = null;

    #[ORM\Column]
    private ?int $yardArea = null;

    #[ORM\Column(nullable: true)]
    private ?int $floor = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalFloors = null;

    #[ORM\Column]
    private ?bool $isFurnished = null;

    #[ORM\Column]
    private ?bool $hasParking = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $yearBuilt = null;

    #[ORM\Column(type: 'string', enumType: ResidentialBuildTypesEnum::class)]
    private ResidentialBuildTypesEnum $buildType;

    public function getIsFurnished(): ?bool
    {
        return $this->isFurnished;
    }

    public function setIsFurnished(?bool $isFurnished): void
    {
        $this->isFurnished = $isFurnished;
    }

    public function getBuildType(): ResidentialBuildTypesEnum
    {
        return $this->buildType;
    }

    public function setBuildType(ResidentialBuildTypesEnum $buildType): void
    {
        $this->buildType = $buildType;
    }


    public function getResidentialType(): ResidentialTypesEnum
    {
        return $this->residentialType;
    }

    public function setResidentialType(ResidentialTypesEnum $residentialType): void
    {
        $this->residentialType = $residentialType;
    }

    public function getPropertyId(): ?Property
    {
        return $this->propertyId;
    }

    public function setPropertyId(Property $propertyId): static
    {
        $this->propertyId = $propertyId;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): static
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(int $bedrooms): static
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    public function getBathrooms(): ?int
    {
        return $this->bathrooms;
    }

    public function setBathrooms(int $bathrooms): static
    {
        $this->bathrooms = $bathrooms;

        return $this;
    }

    public function getYardArea(): ?int
    {
        return $this->yardArea;
    }

    public function setYardArea(int $yardArea): static
    {
        $this->yardArea = $yardArea;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(?int $floor): static
    {
        $this->floor = $floor;

        return $this;
    }

    public function getTotalFloors(): ?int
    {
        return $this->totalFloors;
    }

    public function setTotalFloors(?int $totalFloors): static
    {
        $this->totalFloors = $totalFloors;

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

    public function hasParking(): ?bool
    {
        return $this->hasParking;
    }

    public function setHasParking(bool $hasParking): static
    {
        $this->hasParking = $hasParking;

        return $this;
    }

    public function getYearBuilt(): ?\DateTimeInterface
    {
        return $this->yearBuilt;
    }

    public function setYearBuilt(?\DateTimeInterface $yearBuilt): static
    {
        $this->yearBuilt = $yearBuilt;

        return $this;
    }
}
