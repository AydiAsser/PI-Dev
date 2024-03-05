<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('lastName', null, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('phoneNumber', TextType::class, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('region', TextType::class, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('rate', TextType::class, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('specialite', TextType::class, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Medecin' => 'medecin',
                    'Patient' => 'patient',
                ],
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
