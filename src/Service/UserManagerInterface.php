<?php

namespace App\Service;

use App\Entity\User;
use Random\RandomException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

interface UserManagerInterface
{
    public function hashPassword(User $user, ?string $password): void;

    public function storeProfilePicture(User $user, ?UploadedFile $file): void;

    /**
     * @throws RandomException
     */
    public function generateDefaultProfileCode(User $user): void;
}