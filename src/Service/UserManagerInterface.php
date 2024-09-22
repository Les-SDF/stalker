<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

interface UserManagerInterface
{
    public function hashPassword(User $user, ?string $password): void;

    public function storeProfilePicture(User $user, ?UploadedFile $file): void;
}