<?php

namespace App\Form;

use App\Entity\BienImmobilier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class YoyoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numImmo')
            ->add('statut')
            ->add('prix')
            ->add('etat')
            ->add('date')
            ->add('proprietaire')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BienImmobilier::class,
        ]);
    }
}
