<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Random\RandomException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

    public function hashPassword(User    $user,
                                 ?string $password): void
    {
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $password)
        );
    }

    public function storeProfilePicture(User          $user,
                                        ?UploadedFile $file): void
    {
        if ($file != null) {
            $fileName = uniqid() . $file->guessExtension();
            $file->move($this->profilePictureDirectory, $fileName);
            //$user->setProfilePicture($fileName);
        }
    }

    /**
     * @throws RandomException
     */
    public function generateDefaultProfileCode(User $user): void
    {
        do {
            // Moyen plus sécurisé de générer un code aléatoire
            $defaultProfileCode = $this->randomStringGenerator->generate();
        } while ($this->repository->findByProfileCode($defaultProfileCode));
        $user->setDefaultProfileCode($defaultProfileCode);
    }
}