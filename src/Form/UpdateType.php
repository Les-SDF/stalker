<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\Gender;
use App\Enum\Visibility;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
        $genders = Gender::cases();
        $genderChoices = [];
        foreach ($genders as $gender) {
            $genderChoices[$gender->name] = $gender->value;
        }
        $visi = Visibility::cases();
        $visiChoice = [];
        foreach ($visi as $v) {
            $visiChoice[$v->name] = $v->value;
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
            ->add('password', PasswordType::class,[
                'label' => 'Password',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                    new Length(min: 8, max: 30, minMessage: 'Your password must be at least {{ limit }} characters long.',maxMessage: 'Your password must be at least {{ limit }} characters long.'),
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Gender',
                'choices' => $genderChoices,
                'mapped' => false,
            ])
            ->add('visibility', ChoiceType::class, [
                'label' => 'Visibility',
                'choices' => $visiChoice,
                'mapped' => false,
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
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}