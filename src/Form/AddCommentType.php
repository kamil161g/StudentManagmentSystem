<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddCommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextType::class, [
                'label' => 'Treść: ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Podaj treść.'
                    ])
                ]
            ])
            ->add('created_at', DateTimeType::class, array(
                'data' => new \DateTime('now')))
            ->add('submit', SubmitType::class, ['label' => 'Dodaj komentarz']);
    }
}