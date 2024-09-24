<?php

namespace App\Service;

use Random\RandomException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserManagerInterface
{
    public function hashPassword(UserInterface $user, ?string $password): void;

    public function storeProfilePicture(UserInterface $user, ?UploadedFile $file): void;

    public function isProfileCodeAvailable(string $profileCode): bool;

    /**
     * @throws RandomException
     */
    public function generateProfileCode(UserInterface $user): void;
}