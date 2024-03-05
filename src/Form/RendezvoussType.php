<?php

namespace App\Form;

use App\Entity\Rendezvouss;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RendezvoussType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date', DateType::class, [
            'widget' => 'single_text',
            'constraints' => [
                new GreaterThanOrEqual([
                    'value' => 'today', // 'today' signifie la date actuelle
                    'message' => 'La date doit être supérieure ou égale à aujourd\'hui.',
                ]),
            ],
        ])
            ->add('raison')
            ->add('medecins')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rendezvouss::class,
        ]);
    }
}
