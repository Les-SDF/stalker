<?php

namespace App\Entity;

use App\Enum\Gender;
use App\Enum\Sexuality;
use App\Enum\Visibility;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ["email"], message: "The email {{ value }} is already used")]
#[UniqueEntity(fields: ["defaultProfileCode"], message: "The default profile code {{ value }} is already used")]
#[UniqueEntity(fields: ["customProfileCode"], message: "The custom profile code {{ value }} is already used")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 254)]
    #[Assert\Email(message: "The email '{{ value }}' is not a valid email.")]
    #[Assert\NotBlank]
    #[Assert\NotNull]
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
//    #[Assert\Regex(
//        pattern: "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).+$/",
//        message: "The password must contain at least one lowercase letter, one uppercase letter and one digit"
//    )]
//    #[Assert\Length(
//        min: 8,
//        max: 128,
//        minMessage: "The password must be at least 8 characters long",
//        maxMessage: "The password must be at most 128 characters long"
//    )]
    private ?string $password = null;

    #[ORM\Column(type: "string", enumType: Gender::class)]
    private ?Gender $gender = null;

    #[ORM\Column(nullable: true, enumType: Sexuality::class)]
    private ?Sexuality $sexuality = null;

    #[ORM\Column(enumType: Visibility::class)]
    private ?Visibility $visibility = null;

    #[ORM\Column(length: 255)]
    private ?string $defaultProfileCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 4,
        max: 255,
        minMessage: "The custom profile code must be at least 4 characters long",
        maxMessage: "The custom profile code must be at most 255 characters long"
    )]
    private ?string $customProfileCode = null;

    #[ORM\Column]
    private ?DateTimeImmutable $connectedAt = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $editedAt = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $countryCode = null;

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
        return (string) $this->email;
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

    public function getDefaultProfileCode(): ?string
    {
        return $this->defaultProfileCode;
    }

    public function setDefaultProfileCode(string $defaultProfileCode): static
    {
        $this->defaultProfileCode = $defaultProfileCode;

        return $this;
    }

    public function getCustomProfileCode(): ?string
    {
        return $this->customProfileCode;
    }

    public function setCustomProfileCode(string $customProfileCode): static
    {
        $this->customProfileCode = $customProfileCode;

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
}