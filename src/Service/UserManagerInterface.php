<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

interface UserManagerInterface
{
    /**
     * Hashes the password of the given user.
     *
     * @param User $user
     * @param string|null $password
     * @param UserPasswordHasherInterface $passwordHasher
     * @return void
     */
    public function hashPassword(User $user, ?string $password): void;

    /**
     * Stores the profile picture of the given user.
     *
     * @param User $user
     * @param UploadedFile|null $file
     * @param string $profilePictureDirectory
     * @return void
     */
    public function storeProfilePicture(User $user, ?UploadedFile $file): void;
}