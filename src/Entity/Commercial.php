<?php

namespace App\Entity;

use App\Enum\CommercialTypeEnum;
use App\Enum\ResidentialHeatingTypesEnum;
use App\Repository\CommercialRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommercialRepository::class)]
class Commercial
{
    #[ORM\Id]
    #[ORM\OneToOne(inversedBy: 'commercial', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Property $property = null;

    #[ORM\Column]
    private ?int $rooms = null;

    #[ORM\Column]
    private ?int $bathrooms = null;

    #[ORM\Column(nullable: true)]
    private ?int $floor = null;

    #[ORM\Column]
    private ?int $levels = null;

    #[ORM\Column]
    private ?bool $isFurnished = null;

    #[ORM\Column]
    private ?bool $hasParking = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $yearBuilt = null;

    #[ORM\Column(type: 'string', enumType: CommercialTypeEnum::class)]
    private CommercialTypeEnum $commercialType;

    public function getIsFurnished(): ?bool
    {
        return $this->isFurnished;
    }

    public function setIsFurnished(?bool $isFurnished): void
    {
        $this->isFurnished = $isFurnished;
    }

    public function getCommercialType(): CommercialTypeEnum
    {
        return $this->commercialType;
    }

    public function setCommercialType(CommercialTypeEnum $commercialType): void
    {
        $this->commercialType = $commercialType;
    }


    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(Property $property): static
    {
        $this->property = $property;

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

    public function getBathrooms(): ?int
    {
        return $this->bathrooms;
    }

    public function setBathrooms(int $bathrooms): static
    {
        $this->bathrooms = $bathrooms;

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

    public function getLevels(): ?int
    {
        return $this->levels;
    }

    public function setLevels(int $levels): static
    {
        $this->levels = $levels;

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

    public function setYearBuilt(\DateTimeInterface $yearBuilt): static
    {
        $this->yearBuilt = $yearBuilt;

        return $this;
    }
}
