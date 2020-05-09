<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating' , IntegerType::class, $this->getConfiguration('note sur 5' , 'veuillez indiquer votre note de 1 à 5' , [
                'attr' => [
                    'min' => 0 ,
                    'max'  => 5 ,
                    'step' =>1
                ]

            ]))
            ->add('content' , TextareaType::class , $this->getConfiguration('Votre avis' , 'veuillez indiquer votre commentaire '))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
