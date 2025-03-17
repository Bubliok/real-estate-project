<?php

namespace App\Entity;

use App\Enum\LandZoningTypeEnum;
use App\Repository\LandRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LandRepository::class)]
class Land
{
    #[ORM\Id]
    #[ORM\OneToOne(inversedBy: 'land', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Property $propertyId = null;

    #[ORM\Column(type: 'string', enumType: LandZoningTypeEnum::class)]
    private LandZoningTypeEnum $zoningType;
    #[ORM\Column]
    private ?bool $hasElectricity = null;

    #[ORM\Column]
    private ?bool $hasWater = null;

    public function getPropertyId(): ?Property
    {
        return $this->propertyId;
    }

    public function setPropertyId(Property $propertyId): static
    {
        $this->propertyId = $propertyId;

        return $this;
    }

    public function hasElectricity(): ?bool
    {
        return $this->hasElectricity;
    }

    public function setHasElectricity(bool $hasElectricity): static
    {
        $this->hasElectricity = $hasElectricity;

        return $this;
    }

    public function hasWater(): ?bool
    {
        return $this->hasWater;
    }

    public function setHasWater(bool $hasWater): static
    {
        $this->hasWater = $hasWater;

        return $this;
    }
}
