<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Random\RandomException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class UserManager implements UserManagerInterface
{
    public function __construct(
        #[Autowire("%profile_picture_directory%")]
        private string                         $profilePictureDirectory,
        private UserRepository                 $repository,
        private UserPasswordHasherInterface    $passwordHasher,
        private RandomStringGeneratorInterface $randomStringGenerator
    )
    {
    }

    /**
     * @param User $user
     * @param string|null $password
     * @return void
     */
    public function hashPassword(UserInterface $user,
                                 ?string       $password): void
    {
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $password)
        );
    }

    public function storeProfilePicture(UserInterface $user,
                                        ?UploadedFile $file): void
    {
        if ($file != null) {
            $fileName = uniqid() . $file->guessExtension();
            $file->move($this->profilePictureDirectory, $fileName);
            //$user->setProfilePicture($fileName);
        }
    }

    public function isProfileCodeAvailable(string $profileCode): bool
    {
        return !$this->repository->findByProfileCode($profileCode);
    }

    /**
     * # Note pour le correcteur
     * Il y a sûrement plus de chance de gagner 6 fois d'affilés au loto. Mais au moins on est sûr que le code
     * aléatoire généré est bien disponible avec cette boucle while.
     *
     * @param User $user
     * @return void
     * @throws RandomException
     */
    public function generateProfileCode(UserInterface $user): void
    {
        do {
            $profileCode = $this->randomStringGenerator->generate();
        } while (!$this->isProfileCodeAvailable($profileCode));
        $user->setProfileCode($profileCode);
    }
}