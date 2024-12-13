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
     * @var Collection<int, RealEstateOwner>
     */
    #[ORM\OneToMany(targetEntity: RealEstateOwner::class, mappedBy: 'city')]
    private Collection $realEstateOwners;

    /**
     * @var Collection<int, Neighborhood>
     */
    #[ORM\OneToMany(targetEntity: Neighborhood::class, mappedBy: 'city')]
    private Collection $neighborhoods;

    /**
     * @var Collection<int, RealEstateAgent>
     */
    #[ORM\OneToMany(targetEntity: RealEstateAgent::class, mappedBy: 'city')]
    private Collection $realEstateAgents;

    /**
     * @var Collection<int, BrokerCompany>
     */
    #[ORM\OneToMany(targetEntity: BrokerCompany::class, mappedBy: 'city')]
    private Collection $brokerCompanies;

    /**
     * @var Collection<int, RealEstate>
     */
    #[ORM\OneToMany(targetEntity: RealEstate::class, mappedBy: 'city')]
    private Collection $realEstates;

    public function __construct()
    {
        $this->realEstateOwners = new ArrayCollection();
        $this->neighborhoods = new ArrayCollection();
        $this->realEstateAgents = new ArrayCollection();
        $this->brokerCompanies = new ArrayCollection();
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
    public function getRealEstateOwners(): Collection
    {
        return $this->realEstateOwners;
    }

    public function addRealEstateOwner(RealEstateOwner $realEstateOwner): static
    {
        if (!$this->realEstateOwners->contains($realEstateOwner)) {
            $this->realEstateOwners->add($realEstateOwner);
            $realEstateOwner->setCity($this);
        }

        return $this;
    }

    public function removeRealEstateOwner(RealEstateOwner $realEstateOwner): static
    {
        if ($this->realEstateOwners->removeElement($realEstateOwner)) {
            // set the owning side to null (unless already changed)
            if ($realEstateOwner->getCity() === $this) {
                $realEstateOwner->setCity(null);
            }
        }

        return $this;
    }

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
     * @return Collection<int, RealEstateAgent>
     */
    public function getRealEstateAgents(): Collection
    {
        return $this->realEstateAgents;
    }

    public function addRealEstateAgent(RealEstateAgent $realEstateAgent): static
    {
        if (!$this->realEstateAgents->contains($realEstateAgent)) {
            $this->realEstateAgents->add($realEstateAgent);
            $realEstateAgent->setCity($this);
        }

        return $this;
    }

    public function removeRealEstateAgent(RealEstateAgent $realEstateAgent): static
    {
        if ($this->realEstateAgents->removeElement($realEstateAgent)) {
            // set the owning side to null (unless already changed)
            if ($realEstateAgent->getCity() === $this) {
                $realEstateAgent->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BrokerCompany>
     */
    public function getBrokerCompanies(): Collection
    {
        return $this->brokerCompanies;
    }

    public function addBrokerCompany(BrokerCompany $brokerCompany): static
    {
        if (!$this->brokerCompanies->contains($brokerCompany)) {
            $this->brokerCompanies->add($brokerCompany);
            $brokerCompany->setCity($this);
        }

        return $this;
    }

    public function removeBrokerCompany(BrokerCompany $brokerCompany): static
    {
        if ($this->brokerCompanies->removeElement($brokerCompany)) {
            // set the owning side to null (unless already changed)
            if ($brokerCompany->getCity() === $this) {
                $brokerCompany->setCity(null);
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
