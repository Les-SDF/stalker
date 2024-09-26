<?php

namespace App\Entity;

use App\Repository\SocialMediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SocialMediaRepository::class)]
class SocialMedia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, UserSocialMedia>
     */
    #[ORM\OneToMany(targetEntity: UserSocialMedia::class, mappedBy: 'socialMedia', orphanRemoval: true)]
    private Collection $userSocialMedia;

    public function __construct()
    {
        $this->userSocialMedia = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

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
            $userSocialMedium->setSocialMedia($this);
        }

        return $this;
    }

    public function removeUserSocialMedium(UserSocialMedia $userSocialMedium): static
    {
        if ($this->userSocialMedia->removeElement($userSocialMedium)) {
            // set the owning side to null (unless already changed)
            if ($userSocialMedium->getSocialMedia() === $this) {
                $userSocialMedium->setSocialMedia(null);
            }
        }

        return $this;
    }
}
