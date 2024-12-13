<?php

namespace App\Entity;

use App\Repository\NeighborhoodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NeighborhoodRepository::class)]
class Neighborhood
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $neighborhoodName = null;

    #[ORM\ManyToOne(inversedBy: 'neighborhoods')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    /**
     * @var Collection<int, RealEstate>
     */
    #[ORM\OneToMany(targetEntity: RealEstate::class, mappedBy: 'neighborhood')]
    private Collection $realEstates;

    public function __construct()
    {
        $this->realEstates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNeighborhoodName(): ?string
    {
        return $this->neighborhoodName;
    }

    public function setNeighborhoodName(string $neighborhoodName): static
    {
        $this->neighborhoodName = $neighborhoodName;

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
            $realEstate->setNeighborhood($this);
        }

        return $this;
    }

    public function removeRealEstate(RealEstate $realEstate): static
    {
        if ($this->realEstates->removeElement($realEstate)) {
            // set the owning side to null (unless already changed)
            if ($realEstate->getNeighborhood() === $this) {
                $realEstate->setNeighborhood(null);
            }
        }

        return $this;
    }
}
