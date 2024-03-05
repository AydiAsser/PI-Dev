<?php

namespace App\Form;

use App\Entity\Prescriptions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrescriptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('frequance')
            ->add('date_debut')
            ->add('date_fin')
            ->add('patients')
            ->add('Medecins')
            ->add('medicaments')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prescriptions::class,
        ]);
    }
}
