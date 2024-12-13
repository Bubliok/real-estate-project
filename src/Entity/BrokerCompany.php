<?php

namespace App\Entity;

use App\Repository\BrokerCompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BrokerCompanyRepository::class)]
class BrokerCompany
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $companyName = null;

    #[ORM\Column(length: 10)]
    private ?string $companyPhoneNumber = null;

    #[ORM\Column(length: 40)]
    private ?string $companyEmail = null;

    #[ORM\Column(length: 50)]
    private ?string $companyAddress = null;

    #[ORM\ManyToOne(inversedBy: 'brokerCompanies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    /**
     * @var Collection<int, RealEstateAgent>
     */
    #[ORM\OneToMany(targetEntity: RealEstateAgent::class, mappedBy: 'company')]
    private Collection $realEstateAgents;

    public function __construct()
    {
        $this->realEstateAgents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): static
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getCompanyPhoneNumber(): ?string
    {
        return $this->companyPhoneNumber;
    }

    public function setCompanyPhoneNumber(string $companyPhoneNumber): static
    {
        $this->companyPhoneNumber = $companyPhoneNumber;

        return $this;
    }

    public function getCompanyEmail(): ?string
    {
        return $this->companyEmail;
    }

    public function setCompanyEmail(string $companyEmail): static
    {
        $this->companyEmail = $companyEmail;

        return $this;
    }

    public function getCompanyAddress(): ?string
    {
        return $this->companyAddress;
    }

    public function setCompanyAddress(string $companyAddress): static
    {
        $this->companyAddress = $companyAddress;

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
            $realEstateAgent->setCompany($this);
        }

        return $this;
    }

    public function removeRealEstateAgent(RealEstateAgent $realEstateAgent): static
    {
        if ($this->realEstateAgents->removeElement($realEstateAgent)) {
            // set the owning side to null (unless already changed)
            if ($realEstateAgent->getCompany() === $this) {
                $realEstateAgent->setCompany(null);
            }
        }

        return $this;
    }
}
