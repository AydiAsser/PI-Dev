<?php


namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Reclamation;
use Symfony\Component\Form\Extension\Core\Type\DateType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class Reclamation1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sujet')
            ->add('description')
            ->add('dateCreation', DateType::class, [
                'disabled' => true, // Désactiver le champ de date
                // Autres options du champ
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Ajouter une image',
                'required' => false,
            ]);
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}