<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\Gender;
use App\Enum\Sexuality;
use App\Enum\Visibility;
use App\Service\CountryServiceInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    public function __construct(private readonly CountryServiceInterface $countryService)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        dd($this->countryService->getCountries());
        $builder
            ->add('email', EmailType::class)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('countryCode', ChoiceType::class, [
                'label' => 'Country',
                'choices' => $this->countryService->getCountries(),
                'mapped' => false,
            ])
            ->add('pronouns', TextType::class)
            ->add('gender', ChoiceType::class, [
                'label' => 'Gender',
                'choices' => $this->enumsToChoices(Gender::cases()),
                'mapped' => false,
            ])
            ->add('sexuality', ChoiceType::class, [
                'label' => 'Sexuality',
                'choices' => $this->enumsToChoices(Sexuality::cases()),
                'mapped' => false,
            ])
            ->add('visibility', ChoiceType::class, [
                'label' => 'Visibility',
                'choices' => $this->enumsToChoices(Visibility::cases()),
                'mapped' => false,
            ])
            ->add('phoneNumber', NumberType::class)
            ->add('submit', SubmitType::class);
        ;
    }

    public function enumsToChoices(array $enum): array
    {
        foreach ($enum as $e) {
            $choices[$e->name] = $e->value;
        }
        return $choices ?? [];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}