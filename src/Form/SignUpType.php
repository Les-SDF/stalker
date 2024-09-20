<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\Visibility;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignUpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'autofocus' => true
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true
            ])
            ->add('visibility', CheckboxType::class, [
                'required' => false,
                'mapped' => false,
                'data' => $builder->getData()->getVisibility() === Visibility::Private
            ])
            ->add('submit', SubmitType::class);

        // Permet de convertir la valeur du champ visibility en une valeur de l'enum Visibility
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            $visibilityCheckboxValue = $form->get('visibility')->getData();

            if ($visibilityCheckboxValue) {
                $data->setVisibility(Visibility::Private);
            } else {
                $data->setVisibility(Visibility::Public);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}