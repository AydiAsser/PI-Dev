<?php

namespace App\Form;

use App\Entity\Commentaire;
use App\Entity\Article;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu')
            // ->add('created_at')
            ->add('article', EntityType::class, [
                'class' => Article::class,
                'choice_label' => 'articleTitleWithId', // Use the custom method
            ])
            // ->add('commenter', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'userNameWithId', // Use the custom method
            // ]);
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
