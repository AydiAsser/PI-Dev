<?php

namespace App\Form;

use App\Entity\Medicament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MedicamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
           


            ->add('image', FileType::class, [
                'label' => 'Transport_Picture',
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'accept' => 'image/*', 
                ],
            ])

            ->add('description')
            ->add('prix')
            ->add('categorie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medicament::class,
        ]);
    }
}
