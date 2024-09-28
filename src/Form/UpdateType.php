<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\Gender;
use App\Enum\Sexuality;
use App\Enum\Visibility;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;

class UpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $countryChoices = [];
        foreach ($options['countries'] as $country) {
            $countryChoices[$country['country']] = $country['countryCode'];
        }

        $builder
            ->add('email', EMAILType::class, [
                'label' => 'Email',
                'mapped' => true,
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                    new Email(['message' => 'Please enter a valid email address']),
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Firstname',
                'mapped' => true,
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Lastname',
                'mapped' => true,
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Gender',
                'choices' => Gender::cases(),
                'choice_label' => function(Gender $gender) {
                    return $gender->name;
                },
                'choice_value' => function(?Gender $gender) {
                    return $gender ? $gender->value : '';
                },
                'mapped' => true,
            ])
            ->add('sexuality', ChoiceType::class, [
                'label' => 'Sexuality',
                'choices' => Sexuality::cases(),
                'choice_label' => function(Sexuality $sexuality) {
                    return $sexuality->name;
                },
                'choice_value' => function(?Sexuality $sexuality) {
                    return $sexuality ? $sexuality->value : '';
                },
                'mapped' => true,
            ])
            ->add('visibility', ChoiceType::class, [
                'label' => 'Visibility',
                'choices' => Visibility::cases(),
                'choice_label' => function(Visibility $visibility) {
                    return $visibility->name;
                },
                'choice_value' => function(?Visibility $visibility) {
                    return $visibility ? $visibility->value : '';
                },
                'mapped' => true,
            ])
            ->add('countryCode', ChoiceType::class, [
                'label' => 'Country',
                'choices' => $countryChoices,
                'mapped' => true,
            ])
            ->add('profilePicture', FileType::class, [
                'label' => 'Profile photo',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'accept' => 'image/png, image/jpeg, image/jpg',
                ],
                'constraints' => [
                    new File(maxSize: '10M', maxSizeMessage: 'File too large. Maximum upload size is {{ limit }} MB', extensions: ['png', 'jpg', 'jpeg'], extensionsMessage: ' format not allowed')
                ],
            ])
            ->add('phoneNumber', NumberType::class,
            [
                'label' => 'Phone number',
                'mapped' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'countries' => [],
        ]);
    }
}