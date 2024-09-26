<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class FormManager implements FormManagerInterface
{
    public function __construct(private FormFactoryInterface  $formFactory,
                                private UrlGeneratorInterface $router)
    {
    }

    public function createForm(string $type,
                               string $route,
                               array  $parameters = [],
                               string $method = 'POST',
                               array  $options = []): FormInterface
    {
        $defaultOptions = [
            'method' => $method,
            'action' => $this->router->generate($route, $parameters),
        ];
        return $this->formFactory->create(
            type: $type,
            data: new User(),
            options: array_merge($defaultOptions, $options)
        );
    }
}