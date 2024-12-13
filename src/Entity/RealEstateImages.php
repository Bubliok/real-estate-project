<?php

namespace App\Entity;

use App\Repository\RealEstateImagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RealEstateImagesRepository::class)]
class RealEstateImages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'realEstateImages')]
    private ?RealEstate $RealEstate = null;

    #[ORM\Column(length: 255)]
    private ?string $imagePath = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRealEstate(): ?RealEstate
    {
        return $this->RealEstate;
    }

    public function setRealEstate(?RealEstate $RealEstate): static
    {
        $this->RealEstate = $RealEstate;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }
}
