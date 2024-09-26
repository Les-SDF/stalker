<?php

namespace App\Twig\Extension;

use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Form\UpdateCodeType;
use App\Form\UpdateType;
use App\Service\FormManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\FormView;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormExtension extends AbstractExtension
{
    public function __construct(private readonly FormManagerInterface $formManager,
                                private readonly Security             $security,
                                #[Autowire('%strict_validation%')]
                                private readonly bool                 $strictValidation)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('signInForm', [$this, 'getSignInForm']),
            new TwigFunction('signUpForm', [$this, 'getSignUpForm']),
            new TwigFunction('updateForm', [$this, 'getUpdateForm']),
            new TwigFunction('updateCodeForm', [$this, 'getUpdateCodeForm']),
        ];
    }

    public function getSignUpForm(): FormView
    {
        return $this->formManager->createForm(
            type: SignUpType::class,
            route: 'sign_up',
            options: [
                'validation_groups' => $this->strictValidation
                    ? ['Default', 'strict_validation']
                    : ['Default']
            ]
        )->createView();
    }

    public function getSignInForm(): FormView
    {
        return $this->formManager->createForm(SignInType::class, 'sign_in')->createView();
    }

    public function getUpdateForm(): FormView
    {
        return $this->formManager->createForm(UpdateType::class, 'update_user')->createView();
    }

    public function getUpdateCodeForm(): FormView
    {
        /**
         * @var User $user
         */
        $user = $this->security->getUser();
        return $this->formManager->createForm(UpdateCodeType::class, 'update_profile_code')->createView();
    }
}