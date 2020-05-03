<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends ApplicationType
{
    private $transformer ;

    public  function __construct(FrenchToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer ;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate' , TextType::class  , $this->getConfiguration("Date d'arrivée" , "date d'arrivée"))
            ->add('endDate' , TextType::class  , $this->getConfiguration("Date départ" , "Date départ"))
            ->add('comment' , TextareaType::class , $this->getConfiguration(false , "Commentaire" , ["required" => false]))

        ;
        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
