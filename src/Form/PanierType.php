<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use App\Entity\Panier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('image')
            ->add('prix')
            ->add('description')
            ->add('categorie')
            ->add('quantite', IntegerType::class, [
                'data' => 1,
            ])
            ->add('client')
            ->add('medi')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
