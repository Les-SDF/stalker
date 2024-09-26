<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;

interface FlashMessageHelperInterface
{
    public function addFormErrorsAsFlashMessages(FormInterface $form): void;
    public function addFlashMessage(string $content, string $type = 'error'): void;
}