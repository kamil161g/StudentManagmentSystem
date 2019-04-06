<?php

namespace App\Form;


use App\Entity\Module;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Tytył: ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj tytuł.'
                    ])
                ]
            ])
            ->add('text', TextType::class, [
                'label' => 'Treść: ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj treść.'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, ['label' => 'Dodaj']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver
           ->setDefaults([
               'data_class' => Module::class
           ]);
    }
}