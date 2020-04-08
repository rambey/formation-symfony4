<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName' ,TextType::class ,$this->getConfiguration("prenom" ,"votre prénom..."))
            ->add('lastName' ,TextType::class , $this->getConfiguration("nom" , "votre nom ..."))
            ->add('email' , EmailType::class, $this->getConfiguration("Email" , "email@email.com"))
            ->add('picture' , UrlType::class , $this->getConfiguration("photo de profile" , "url de votre photo"))
            ->add('hash',PasswordType::class, $this->getConfiguration("password" , "votre password"))
            ->add('passwordConfirm' , PasswordType::class,$this->getConfiguration("confirmation mot de passe","Veuillez confirmez votre mot de passe"))
            ->add('introduction' , TextType::class , $this->getConfiguration("introduction" , "votre introduction"))
            ->add('description' , TextareaType::class , $this->getConfiguration("description detaillé" ,""))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
