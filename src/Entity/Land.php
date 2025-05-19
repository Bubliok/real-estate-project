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
    private ?Property $property = null;

    #[ORM\Column(type: 'string', enumType: LandZoningTypeEnum::class)]
    private LandZoningTypeEnum $zoningType;
    #[ORM\Column]
    private ?bool $hasElectricity = null;

    #[ORM\Column]
    private ?bool $hasWater = null;

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(Property $property): static
    {
        $this->property = $property;

        return $this;
    }

    public function getZoningType(): LandZoningTypeEnum
    {
        return $this->zoningType;
    }

    public function setZoningType(LandZoningTypeEnum $zoningType): static
    {
        $this->zoningType = $zoningType;

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
