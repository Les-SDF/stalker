<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;

interface FormManagerInterface
{
    public function createForm(string $type, string $route, array $parameters = [], string $method = 'POST', array $options = []): FormInterface;
}