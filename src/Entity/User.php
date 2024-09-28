<?php

namespace App\Entity;

use App\Enum\Gender;
use App\Enum\Sexuality;
use App\Enum\Visibility;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_LOGIN', fields: ['login'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_PROFILE_CODE', fields: ['profileCode'])]
#[UniqueEntity(fields: ["login"], message: "The login {{ value }} is already used")]
#[UniqueEntity(fields: ["email"], message: "The email {{ value }} is already used")]
#[UniqueEntity(fields: ["profileCode"], message: "The profile code {{ value }} is already used")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 16)]
    #[Assert\Regex(
        pattern: "/^[a-z0-9]+$/",
        message: "The login must contain only lowercase letters and digits"
    )]
    #[Assert\Length(
        min: 2,
        max: 16,
        minMessage: "The login must be at least 2 characters long",
        maxMessage: "The login must be at most 16 characters long"
    )]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Groups(['user_public'])]
    private ?string $login = null;

    #[ORM\Column(length: 254)]
    #[Assert\Email(
        message: "The email '{{ value }}' is not a valid email."
    )]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Groups(['user_public'])]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string|null The hashed password
     */
    #[ORM\Column]
    #[Assert\Regex(
        pattern: "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).+$/",
        message: "The password must contain at least one lowercase letter, one uppercase letter and one digit",
        groups: ["strict_validation"]
    )]
    #[Assert\Length(
        min: 8,
        max: 128,
        minMessage: "The password must be at least 8 characters long",
        maxMessage: "The password must be at most 128 characters long",
        groups: ["strict_validation"]
    )]
    private ?string $password = null;

    #[ORM\Column(nullable: true, enumType: Gender::class)]
    #[Groups(['user_public'])]
    private ?Gender $gender = null;

    #[ORM\Column(nullable: true, enumType: Sexuality::class)]
    #[Groups(['user_public'])]
    private ?Sexuality $sexuality = null;

    #[ORM\Column(enumType: Visibility::class)]
    #[Groups(['user_public'])]
    private ?Visibility $visibility = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 4,
        max: 255,
        minMessage: "The profile code must be at least 4 characters long",
        maxMessage: "The profile code must be at most 255 characters long"
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9]+$/",
        message: "The profile code must contain only letters and digits"
    )]
    #[Groups(['user_public'])]
    private ?string $profileCode = null;

    #[ORM\Column]
    #[Groups(['user_public'])]
    private ?DateTimeImmutable $connectedAt = null;

    #[ORM\Column]
    #[Groups(['user_public'])]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['user_public'])]
    private ?DateTimeImmutable $editedAt = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['user_public'])]
    private ?string $phoneNumber = null;

    /**
     * ISO 3166-1 alpha-2 country code
     */
    #[ORM\Column(length: 2, nullable: true)]
    #[Groups(['user_public'])]
    private ?string $countryCode = null;

    #[ORM\Column(length: 32, nullable: true)]
    #[Groups(['user_public'])]
    private ?string $pronouns = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user_public'])]
    private ?string $aboutMe = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user_public'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user_public'])]
    private ?string $lastname = null;

    /**
     * @var Collection<int, UserSocialMedia>
     */
    #[ORM\OneToMany(targetEntity: UserSocialMedia::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $userSocialMedia;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ProfilePicture = null;

    public function __construct()
    {
        $this->userSocialMedia = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
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

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(Gender $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getVisibility(): ?Visibility
    {
        return $this->visibility;
    }

    public function setVisibility(Visibility $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getProfileCode(): ?string
    {
        return $this->profileCode;
    }

    public function setProfileCode(string $profileCode): static
    {
        $this->profileCode = $profileCode;

        return $this;
    }

    public function getConnectedAt(): ?DateTimeImmutable
    {
        return $this->connectedAt;
    }

    public function setConnectedAt(DateTimeImmutable $connectedAt): static
    {
        $this->connectedAt = $connectedAt;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEditedAt(): ?DateTimeImmutable
    {
        return $this->editedAt;
    }

    public function setEditedAt(?DateTimeImmutable $editedAt): static
    {
        $this->editedAt = $editedAt;

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

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): static
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getSexuality(): ?Sexuality
    {
        return $this->sexuality;
    }

    public function setSexuality(?Sexuality $sexuality): static
    {
        $this->sexuality = $sexuality;

        return $this;
    }

    public function getPronouns(): ?string
    {
        return $this->pronouns;
    }

    public function setPronouns(?string $pronouns): static
    {
        $this->pronouns = $pronouns;

        return $this;
    }

    public function getAboutMe(): ?string
    {
        return $this->aboutMe;
    }

    public function setAboutMe(?string $aboutMe): static
    {
        $this->aboutMe = $aboutMe;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return Collection<int, UserSocialMedia>
     */
    public function getUserSocialMedia(): Collection
    {
        return $this->userSocialMedia;
    }

    public function addUserSocialMedium(UserSocialMedia $userSocialMedium): static
    {
        if (!$this->userSocialMedia->contains($userSocialMedium)) {
            $this->userSocialMedia->add($userSocialMedium);
            $userSocialMedium->setUser($this);
        }

        return $this;
    }

    public function removeUserSocialMedium(UserSocialMedia $userSocialMedium): static
    {
        if ($this->userSocialMedia->removeElement($userSocialMedium)) {
            // set the owning side to null (unless already changed)
            if ($userSocialMedium->getUser() === $this) {
                $userSocialMedium->setUser(null);
            }
        }

        return $this;
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(string $role): self
    {
        if (($key = array_search($role, $this->roles, true)) !== false) {
            unset($this->roles[$key]);
        }

        $this->roles = array_values($this->roles);

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->ProfilePicture;
    }

    public function setProfilePicture(?string $ProfilePicture): static
    {
        $this->ProfilePicture = $ProfilePicture;

        return $this;
    }
}