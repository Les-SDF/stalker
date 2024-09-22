<?php

namespace App\Form;

use App\Entity\User;
use libphonenumber\PhoneNumberType;
use MongoDB\BSON\Regex;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
 //           ->add('gender')
 //           ->add('visibility')
            ->add('customProfileCode', FileType::class, [
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
            ->add('countryCode')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}