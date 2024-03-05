<?php

namespace App\Form;

use App\Entity\PlanningMedecins;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;



class PlanningMedecinsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('jour_debut', ChoiceType::class, [
            'choices' => [
                'Lundi' => 'Lundi',
                'Mardi' => 'Mardi',
                'Mercredi' => 'Mercredi',
                'Jeudi' => 'Jeudi',
                'Vendredi' => 'Vendredi',
                'Samedi' => 'Samedi',
                'Dimanche' => 'Dimanche',
            ],
            'constraints' => [
                new Choice([
                    'choices' => ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'],
                    'message' => 'Veuillez choisir un jour valide de la semaine.',
                ]),
            ],
        ])
        ->add('jour_fin', ChoiceType::class, [
            'choices' => [
                'Lundi' => 'Lundi',
                'Mardi' => 'Mardi',
                'Mercredi' => 'Mercredi',
                'Jeudi' => 'Jeudi',
                'Vendredi' => 'Vendredi',
                'Samedi' => 'Samedi',
                'Dimanche' => 'Dimanche',
            ],
            'constraints' => [
                new Choice([
                    'choices' => ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'],
                    'message' => 'Veuillez choisir un jour valide de la semaine.',
                ]),
            ],
        ])
           
            ->add('heure_debut')
            ->add('heure_fin')
            ->add('disponibilite')
            
           
           

            ->add('User', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.role = :role')
                        ->setParameter('role', 'Medecin');
                },
           
            ])





        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlanningMedecins::class,
        ]);
    }
}
