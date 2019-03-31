<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj Email.',
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Twoje hasło nie może być krótsze niż {{ limit }}.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Twoje hasło nie może być krótsze niż 6 znaków.',
                        'max' => 4096,
                        'maxMessage' => 'Twoje hasło nie może być dłuższe niz 4096 znaków.'
                    ]),
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Imię:',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj imię.'
                    ]),
                ]
            ])
            ->add('surname', TextType::class, [
                'label' => 'Nazwisko:',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj nazwisko.'
                    ]),
                ]
            ])
            ->add('age', DateType::class, [
                'label' => 'Data urodzenia:',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj swój wiek.'
                    ]),
                ]
            ])
            ->add('class', ChoiceType::class, [
                'choices'  => [
                    'Informatyczna' => 'Informatyczna',
                    'Biologiczna' => 'Biologiczna',
                    'Chemiczna' => 'Chemiczna',
                ]
            ])
            ->add('file', FileType::class, [
                'label' => 'Registration',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj skan legitymacji.'
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, ['label' => 'Zarejestruj się' ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
