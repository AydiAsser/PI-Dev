<?php

namespace App\Form;

use App\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title')
        ->add('start', DateTimeType::class, [
            'date_widget' => 'single_text',
            'constraints' => [
                new GreaterThanOrEqual([
                    'value' => 'today', // 'today' signifie la date actuelle
                    'message' => 'La date doit être supérieure ou égale à aujourd\'hui.',
                ]),
            ],

        ])
        ->add('end', DateTimeType::class, [
            'date_widget' => 'single_text',
            'constraints' => [
                new GreaterThanOrEqual([
                    'value' => 'today', // 'today' signifie la date actuelle
                    'message' => 'La date doit être supérieure ou égale à aujourd\'hui.',
                ]),
            ],
        ])
        ->add('description')
        ->add('all_day')
        ->add('backgroung_color', ColorType::class)
        ->add('border_color', ColorType::class)
        ->add('text_color', ColorType::class)
            
            

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
            'data_class' => Calendar::class,
        ]);
    }
}