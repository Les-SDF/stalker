<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class FlashMessageHelper implements FlashMessageHelperInterface
{

    public function __construct(
        private RequestStack $requestStack
    )
    {
    }

    public function addFormErrorsAsFlashMessages(FormInterface $form): void
    {
        foreach ($form->getErrors(true) as $error) {
            $this->requestStack->getSession()->getFlashBag()->add('error', $error->getMessage());
        }
    }

    public function addFlashMessage(string $content, string $type = 'error'): void
    {
        $this->requestStack->getSession()->getFlashBag()->add($type, $content);
    }
}