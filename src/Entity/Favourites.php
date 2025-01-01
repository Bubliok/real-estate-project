<?php

namespace App\Entity;

use App\Repository\FavouritesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavouritesRepository::class)]
class Favourites
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'favourites')]
    private ?User $userId = null;

    #[ORM\ManyToOne(inversedBy: 'favourites')]
    private ?RealEstate $estateId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getEstateId(): ?RealEstate
    {
        return $this->estateId;
    }

    public function setEstateId(?RealEstate $estateId): static
    {
        $this->estateId = $estateId;

        return $this;
    }
}
