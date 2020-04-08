<?php

namespace App\Form;

use App\Entity\Ad;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title' ,TextType::class , $this->getConfiguration("Titre" , "Tapez un titre pour votre annonce"))
            ->add('slug' ,TextType::class , $this->getConfiguration("Champ url " , "Adresse web (automatique)" , [
                'required' => false
            ]) )
            ->add('coverImage' , UrlType::class , $this->getConfiguration("Image" , "Tapez une adresse denl'image  pour votre annonce"))
            ->add('introduction' , TextType::class , $this->getConfiguration("Introduction" , "Tapez une description globale de l'annonce"))
            ->add('content' , TextareaType::class , $this->getConfiguration("Contenu" , "Tapez une description detaillé de l'annonce"))
            ->add('rooms' , IntegerType::class , $this->getConfiguration("Nombre de chambres" , "Précisez le nombre de chambre de l'annonce"))
            ->add('price' , MoneyType::class,$this->getConfiguration("Prix par nuit" , "Prix de l'annonce"))
            ->add(
                'images' ,
                 CollectionType::class,
                  [
                      'entry_type'  => ImageType::class ,
                      'allow_add' => true ,
                      'allow_delete' => true
                  ]
                )

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
