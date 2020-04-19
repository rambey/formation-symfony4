<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate' , DateType::class  , $this->getConfiguration("Date d'arrivée" , "date d'arrivée" , ["widget" =>"single_text"]))
            ->add('endDate' , DateType::class , $this->getConfiguration("Date départ" , "Date départ" , ["widget" =>"single_text"]))
            ->add('comment' , TextareaType::class , $this->getConfiguration(false , "Commentaire"))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}