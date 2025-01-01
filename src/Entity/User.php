<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;


    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $profileImage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullName = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $email = null;

    /**
     * @var Collection<int, Favourites>
     */
    #[ORM\OneToMany(targetEntity: Favourites::class, mappedBy: 'userId')]
    private Collection $favourites;

    /**
     * @var Collection<int, RealEstate>
     */
    #[ORM\OneToMany(targetEntity: RealEstate::class, mappedBy: 'userId')]
    private Collection $realEstates;

    public function __construct()
    {
        $this->favourites = new ArrayCollection();
        $this->realEstates = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getProfileImage(): ?string
    {
        return $this->profileImage;
    }

    public function setProfileImage(string $profileImage): static
    {
        $this->profileImage = $profileImage;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Favourites>
     */
    public function getFavourites(): Collection
    {
        return $this->favourites;
    }

    public function addFavourite(Favourites $favourite): static
    {
        if (!$this->favourites->contains($favourite)) {
            $this->favourites->add($favourite);
            $favourite->setUserId($this);
        }

        return $this;
    }

    public function removeFavourite(Favourites $favourite): static
    {
        if ($this->favourites->removeElement($favourite)) {
            // set the owning side to null (unless already changed)
            if ($favourite->getUserId() === $this) {
                $favourite->setUserId(null);
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
            $realEstate->setUserId($this);
        }

        return $this;
    }

    public function removeRealEstate(RealEstate $realEstate): static
    {
        if ($this->realEstates->removeElement($realEstate)) {
            // set the owning side to null (unless already changed)
            if ($realEstate->getUserId() === $this) {
                $realEstate->setUserId(null);
            }
        }

        return $this;
    }
}
