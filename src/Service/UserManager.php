<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserManager implements UserManagerInterface
{
    public function __construct(
        #[Autowire("%profile_picture_directory%")]
        private string                      $profilePictureDirectory,
    )
    {
    }

    public function hashPassword(User                        $user,
                                 ?string                     $password,
                                 UserPasswordHasherInterface $passwordHasher): void
    {
        $user->setPassword(
            $passwordHasher->hashPassword($user, $password)
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
}