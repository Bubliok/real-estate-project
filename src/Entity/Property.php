<?php

namespace App\Entity;

use App\Enum\ListingTypeEnum;
use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $area = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    /**
     * @var Collection<int, UserFavorites>
     */
    #[ORM\OneToMany(targetEntity: UserFavorites::class, mappedBy: 'propertyId')]
    private Collection $userFavorites;

    /**
     * @var Collection<int, PropertyImages>
     */
    #[ORM\OneToMany(targetEntity: PropertyImages::class, mappedBy: 'propertyId')]
    private Collection $propertyImages;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    private ?Region $regionId = null;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $cityId = null;

    #[ORM\OneToOne(mappedBy: 'property', cascade: ['persist', 'remove'])]
    private ?Residential $residential = null;
    #[ORM\OneToOne(mappedBy: 'property', cascade: ['persist', 'remove'])]
    private ?Commercial $commercial = null;

    #[ORM\OneToOne(mappedBy: 'property', cascade: ['persist', 'remove'])]
    private ?Land $land = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', enumType: ListingTypeEnum::class)]
    private ListingTypeEnum $listingType;

    #[ORM\Column]
    private ?int $views = 0;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $modifiedAt = null;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    #[ORM\JoinColumn(name: 'user_id' ,nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Feature>
     */
    #[ORM\ManyToMany(targetEntity: Feature::class, inversedBy: 'properties')]
    private Collection $features;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function __construct()
    {
        $this->userFavorites = new ArrayCollection();
        $this->propertyImages = new ArrayCollection();
        $this->features = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getListingType(): ListingTypeEnum
    {
        return $this->listingType;
    }

    public function setListingType(ListingTypeEnum $listingType): void
    {
        $this->listingType = $listingType;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(?int $views): void
    {
        $this->views = $views;
    }



    public function getArea(): ?int
    {
        return $this->area;
    }

    public function setArea(int $area): static
    {
        $this->area = $area;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, UserFavorites>
     */
    public function getUserFavorites(): Collection
    {
        return $this->userFavorites;
    }

    public function getResidential(): ?Residential
    {
        return $this->residential;
    }

    public function setResidential(?Residential $residential): static
    {
        // set the owning side of the relation if necessary
        if ($residential->getProperty() !== $this) {
            $residential->setProperty($this);
        }

        $this->residential = $residential;

        return $this;
    }

    public function addUserFavorite(UserFavorites $userFavorite): static
    {
        if (!$this->userFavorites->contains($userFavorite)) {
            $this->userFavorites->add($userFavorite);
            $userFavorite->setPropertyId($this);
        }

        return $this;
    }

    public function removeUserFavorite(UserFavorites $userFavorite): static
    {
        if ($this->userFavorites->removeElement($userFavorite)) {
            // set the owning side to null (unless already changed)
            if ($userFavorite->getPropertyId() === $this) {
                $userFavorite->setPropertyId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PropertyImages>
     */
    public function getPropertyImages(): Collection
    {
        return $this->propertyImages;
    }

    public function addPropertyImage(PropertyImages $propertyImage): static
    {
        if (!$this->propertyImages->contains($propertyImage)) {
            $this->propertyImages->add($propertyImage);
            $propertyImage->setPropertyId($this);
        }

        return $this;
    }

    public function removePropertyImage(PropertyImages $propertyImage): static
    {
        if ($this->propertyImages->removeElement($propertyImage)) {
            // set the owning side to null (unless already changed)
            if ($propertyImage->getPropertyId() === $this) {
                $propertyImage->setPropertyId(null);
            }
        }

        return $this;
    }

    public function getRegionId(): ?Region
    {
        return $this->regionId;
    }

    public function setRegionId(?Region $regionId): static
    {
        $this->regionId = $regionId;

        return $this;
    }

    public function getCityId(): ?City
    {
        return $this->cityId;
    }

    public function setCityId(?City $cityId): static
    {
        $this->cityId = $cityId;

        return $this;
    }

    public function getCommercial(): ?Commercial
    {
        return $this->commercial;
    }

    public function setCommercial(?Commercial $commercial): static
    {
        // set the owning side of the relation if necessary
        if ($commercial->getProperty() !== $this) {
            $commercial->setProperty($this);
        }

        $this->commercial = $commercial;

        return $this;
    }

    public function getLand(): ?Land
    {
        return $this->land;
    }

    public function setLand(?Land $land): static
    {
        // set the owning side of the relation if necessary
        if ($land->getProperty() !== $this) {
            $land->setProperty($this);
        }

        $this->land = $land;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(\DateTimeImmutable $modifiedAt): static
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Feature>
     */
    public function getFeatures(): Collection
    {
        return $this->features;
    }

    public function addFeature(Feature $feature): static
    {
        if (!$this->features->contains($feature)) {
            $this->features->add($feature);
        }

        return $this;
    }

    public function removeFeature(Feature $feature): static
    {
        $this->features->removeElement($feature);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
