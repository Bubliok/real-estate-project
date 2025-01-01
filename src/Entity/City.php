<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $cityName = null;

    /**
     * @var Collection<int, Neighborhood>
     */
    #[ORM\OneToMany(targetEntity: Neighborhood::class, mappedBy: 'city')]
    private Collection $neighborhoods;

    #[ORM\OneToMany(targetEntity: RealEstate::class, mappedBy: 'city')]
    private Collection $realEstates;

    public function __construct()
    {
        $this->neighborhoods = new ArrayCollection();
        $this->realEstates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    public function setCityName(string $cityName): static
    {
        $this->cityName = $cityName;

        return $this;
    }

    /**
     * @return Collection<int, RealEstateOwner>
     */

    /**
     * @return Collection<int, Neighborhood>
     */
    public function getNeighborhoods(): Collection
    {
        return $this->neighborhoods;
    }

    public function addNeighborhood(Neighborhood $neighborhood): static
    {
        if (!$this->neighborhoods->contains($neighborhood)) {
            $this->neighborhoods->add($neighborhood);
            $neighborhood->setCity($this);
        }

        return $this;
    }

    public function removeNeighborhood(Neighborhood $neighborhood): static
    {
        if ($this->neighborhoods->removeElement($neighborhood)) {
            // set the owning side to null (unless already changed)
            if ($neighborhood->getCity() === $this) {
                $neighborhood->setCity(null);
            }
        }

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
            $realEstate->setCity($this);
        }

        return $this;
    }

    public function removeRealEstate(RealEstate $realEstate): static
    {
        if ($this->realEstates->removeElement($realEstate)) {
            // set the owning side to null (unless already changed)
            if ($realEstate->getCity() === $this) {
                $realEstate->setCity(null);
            }
        }

        return $this;
    }
}
