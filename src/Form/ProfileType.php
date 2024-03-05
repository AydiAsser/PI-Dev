<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', null, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('Email', null, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('Mdp', PasswordType::class, [
                'attr' => ['class' => 'form-control mb-3'],
            ])


            ->add('num', null, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('Adresse', null, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
