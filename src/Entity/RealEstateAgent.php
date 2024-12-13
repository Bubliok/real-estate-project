<?php

namespace App\Entity;

use App\Repository\RealEstateAgentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RealEstateAgentRepository::class)]
class RealEstateAgent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'realEstateAgent', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?WebUser $WebUser = null;

    #[ORM\Column(length: 40)]
    private ?string $agentName = null;

    #[ORM\Column(length: 10)]
    private ?string $agentPhoneNumber = null;

    #[ORM\Column(length: 40)]
    private ?string $agentEmail = null;

    #[ORM\ManyToOne(inversedBy: 'realEstateAgents')]
    private ?City $city = null;

    #[ORM\Column]
    private ?float $agentCommission = null;

    #[ORM\ManyToOne(inversedBy: 'realEstateAgents')]
    private ?BrokerCompany $company = null;

    /**
     * @var Collection<int, RealEstate>
     */
    #[ORM\OneToMany(targetEntity: RealEstate::class, mappedBy: 'agent')]
    private Collection $realEstates;

    public function __construct()
    {
        $this->realEstates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebUser(): ?WebUser
    {
        return $this->WebUser;
    }

    public function setWebUser(WebUser $WebUser): static
    {
        $this->WebUser = $WebUser;

        return $this;
    }

    public function getAgentName(): ?string
    {
        return $this->agentName;
    }

    public function setAgentName(string $agentName): static
    {
        $this->agentName = $agentName;

        return $this;
    }

    public function getAgentPhoneNumber(): ?string
    {
        return $this->agentPhoneNumber;
    }

    public function setAgentPhoneNumber(string $agentPhoneNumber): static
    {
        $this->agentPhoneNumber = $agentPhoneNumber;

        return $this;
    }

    public function getAgentEmail(): ?string
    {
        return $this->agentEmail;
    }

    public function setAgentEmail(string $agentEmail): static
    {
        $this->agentEmail = $agentEmail;

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

    public function getAgentCommission(): ?float
    {
        return $this->agentCommission;
    }

    public function setAgentCommission(float $agentCommission): static
    {
        $this->agentCommission = $agentCommission;

        return $this;
    }

    public function getCompany(): ?BrokerCompany
    {
        return $this->company;
    }

    public function setCompany(?BrokerCompany $company): static
    {
        $this->company = $company;

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
            $realEstate->setAgent($this);
        }

        return $this;
    }

    public function removeRealEstate(RealEstate $realEstate): static
    {
        if ($this->realEstates->removeElement($realEstate)) {
            // set the owning side to null (unless already changed)
            if ($realEstate->getAgent() === $this) {
                $realEstate->setAgent(null);
            }
        }

        return $this;
    }
}
