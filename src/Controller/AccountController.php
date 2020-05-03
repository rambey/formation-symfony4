<?php

namespace App\Controller;


use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * permet de se connecter
     * @Route("/login", name="account_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        return $this->render('account/login.html.twig' , [
            'hasError' => $error != null ,
            'username' => $username
        ]);

    }

    /**
     * permet de se déconnecter
     * @Route("/logout" ,name="account_logout")
     * @return void
     */
    public function logout(){

    }

    /**
     * permet d'afficher le formualire d'inscription
     * @Route("register" , name="account_register")
     * @return Response
     */
    public function register(Request $request , EntityManagerInterface $manager , UserPasswordEncoderInterface $encoder){
       $user = new User();
       $form = $this->createForm(RegistrationType::class , $user);
       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user , $user->getHash());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre compte à été bien crée ! Vous pouvez maintenant vous connectez !"
            );
            return $this->redirectToRoute('account_login');

       }
       return  $this->render("account/resgistration.html.twig" , [
           'form' => $form->createView()
       ]);
    }

    /**
     * permet d'afficher et modifier le profile d'un utilisaiteur
     * @Route("/account/profile" , name="account_profile")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function profile(Request $request , EntityManagerInterface $manager){
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success' ,
                "profile utilsateur mis à jour !"
            );
        }

        return $this->render('account/profile.html.twig' , [
            'form' => $form->createView()
        ]);
    }

    /**
     * permet de modifier le mot de passe
     * @Route("/account/password-update" , name="account_password")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public  function updatePassword(Request $request , UserPasswordEncoderInterface $encoder , EntityManagerInterface $manager){
        $passwordUpdate = new PasswordUpdate();
        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()  ){

            if(!password_verify($passwordUpdate->getOldPassword() , $user->getHash())){
                $form->get('OldPassword')->addError(new FormError("le mot de passe que vous avez tapez n'est votre mot de passe actuel !"));
            }else{
                $newPassword =$passwordUpdate->getNewPassword() ;
                $hash = $encoder->encodePassword($user , $newPassword);
                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    'success',
                    "votre mot de passe a été bien modifié !"
                );
                return $this->redirectToRoute('homepage');
            }

        }
        return $this->render("account/password.html.twig" , [
            'form' =>$form->createView()
        ]);
    }

    /**
     * Permt d'afficher le profil d'un utilisateur connecté
     * @Route("/account" , name="account_index")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function  myAccount(){
        if($this->getUser()){
            return $this->render("user/index.html.twig" , [
                'user' => $this->getUser()
            ]);
        }else{
            return $this->redirectToRoute('account_login');
        }

    }

    /**
     * permet la liste des resvations d'un utilisateur
     * @Route("/account/bookings" , name="account_bookings")
     * @return Response
     */
    public function bookings(){
        return $this->render('account/bookings.html.twig');
    }
}
