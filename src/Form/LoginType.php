<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
class LoginType extends AbstractType
{ public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, [
                'attr' => ['class' => 'form-control mb-3'],
            ]) 
            ->add('mdp', PasswordType::class, [
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function getBlockPrefix()
    {
        // This method is necessary to prevent name conflicts with the original UserType
        return '';
    }
}
