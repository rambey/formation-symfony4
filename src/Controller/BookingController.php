<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function book( Request $request ,Ad $ad , EntityManagerInterface $manager)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class , $booking);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){


            $user = $this->getUser();

            $booking->setBooker($user)
                     ->setAd($ad);
            //var_dump($booking->getStartDate());die();
             // si les dates ne sont pas dispo message erreur sinon persisi

            if(!$booking->isBookableDate()){
               $this->addFlash(
                   'warning' ,
                   "les dates renseignes ne peuvent être reservés , elles sont  deja prises ! "
               );
            }else{

                $manager->persist($booking);
                $manager->flush();
                return $this->redirectToRoute('booking_show' , ['id' => $booking->getId(),'success' =>1] );
            }
        }
        return $this->render('booking/book.html.twig', [
              'ad' => $ad ,
             'form'=> $form->createView()
            ]
        );
    }

    /**
     * permet d'afficher la page de reservation
     *
     * @Route("/booking/{id}", name="booking_show")
     * @param Booking $booking
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public  function  show(Booking $booking , Request $request , EntityManagerInterface $manager){
        $comment = new Comment();
        $form = $this->createForm(CommentType::class , $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $comment->setAd($booking->getAd())
                    ->setAuthor($this->getUser());
           $manager->persist($comment);
           $manager->flush();
           $this->addFlash(
               'success',
               'votre commenatire a été bien enregistré'
           );
        }
        return $this->render('booking/show.html.twig' , [
            'booking' => $booking ,
            'form' => $form->createView()
        ]);
    }
}
