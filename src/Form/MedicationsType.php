<?php

namespace App\Form;

use App\Entity\Medications;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\GreaterThan;

class MedicationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('quantite', IntegerType::class, [
            'constraints' => [
                new NotBlank(),
                new PositiveOrZero([
                    'message' => 'La quantité doit être positive ou zéro.',
                ]),
            ],
        ])
        ->add('prix', MoneyType::class, [
            'constraints' => [
                new NotBlank(),
                new GreaterThan([
                    'value' => 0,
                    'message' => 'Le prix doit être supérieur à zéro.',
                ]),
            ],
        ])
        ->add('name', TextType::class, [
            'constraints' => [
                new NotBlank(),
                // Ajoutez d'autres contraintes selon vos besoins
            ],
        ])
        ->add('disponibilite')
        ->add('instruction_usage', TextType::class, [
            'constraints' => [
                // Ajoutez des contraintes selon vos besoins
            ],
        ])
        ->add('prescriptions')
    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medications::class,
        ]);
    }
}
