<?php

namespace App\Controller;


use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     *
     * permet de lister les reservations
     * @Route("/admin/booking/{page<\d+>?1}", name="admin_booking_index")
     * @param BookingRepository $booking
     * @param $page
     * @param PaginationService $pagination
     * @return Response
     */
    public function index(BookingRepository $booking , $page , PaginationService $pagination)
    {
        $pagination->setEntityClass(Booking::class)
            ->setPage($page)
             ;
        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $pagination
        ]);
    }


    /**
     * permet d'éditer une réservation
     * @Route("/admin/booking/{id}/edit" ,  name="admin_booking_edit")
     * @param EntityManagerInterface $manager
     * @param Booking $booking
     * @return response
     */
    public function edit(EntityManagerInterface $manager , Booking $booking , Request $request)
    {
          $form = $this->createForm(AdminBookingType::class , $booking);
          $form->handleRequest($request);
          if($form->isSubmitted() && $form->isValid()){
             $booking->setAmount(0);
             $manager->persist($booking);
             $manager->flush();
             $this->addFlash(
                 'success' ,
                 "la reservation n° {$booking->getId()} a été bien modifié"
             );
          }
          return $this->render('admin/booking/edit.html.twig' ,[
              'form' => $form->createView(),
               'booking' => $booking
          ]);
    }

    /**
     * permet de supprimer une réservation
     * @Route("/admin/booking/{id}/delete" , name="admin_booking_delete")
     * @param EntityManagerInterface $manager
     * @param Booking $booking
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function  delete(EntityManagerInterface $manager , Booking $booking){
          $manager->remove($booking);
          $manager->flush();
          $this->addFlash(
              'success' ,
              "la reservation de <strong>{$booking->getBooker()->getFullName()}</strong>  a été supprimé"
          );
          return  $this->redirectToRoute('admin_booking');
    }
}
