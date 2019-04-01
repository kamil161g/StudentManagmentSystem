<?php


namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordIfYouForgotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('newPassword1', PasswordType::class, [
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
            ->add('newPassword2', PasswordType::class, [
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
            ->add('submit', SubmitType::class, ['label' => 'Zmień hasło']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => User::class
            ]);
    }
}