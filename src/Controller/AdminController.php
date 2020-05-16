<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;

use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     * @param AdRepository $repo
     * @param $page
     * @param PaginationService $pagination
     * @return Response
     */
    public function index(AdRepository $repo , $page , PaginationService $pagination)
    {
        $pagination->setEntityClass(Ad::class)
                   ->setPage($page)
                    ;
        return $this->render('admin/ad/index.html.twig', [
           'pagination' => $pagination
        ]);
    }

    /**
     * permet d'afficher le formualiree d'editon
     * @Route("/admin/ads/{id}/edit" , name="admin_ads_edit")
     * @param Ad $ad
     * @return Response
     */
    public function edit (Ad $ad , Request $request , EntityManagerInterface $manager){
        $form = $this->createForm(AdType::class , $ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash('success' ,
                "la modification de l'annonce a été bien enregistrée"
            );
        }
        return $this->render('admin/ad/edit.html.twig' , [
            'ad' => $ad ,
            'form' => $form->createView()
        ]);
    }

    /**
     * permet de supprimer une annonce
     * @Route("/admin/ads/{id}/delete" , name="admin_ads_delete")
     * @param Ad $ad
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Ad $ad ,Request $request , EntityManagerInterface $manager){
        if(count($ad->getBookings())> 0){
            $this->addFlash(
                'warning' ,
                "vous ne pouvez supprimer l'annonce <strong>{$ad->getTitle()} </strong>, elle contient des réservations !"
            );
        }else{
           $manager->remove($ad);
           $manager->flush();
           $this->addFlash(
               'success' ,
               "l'annonce <strong>{$ad->getTitle()} </strong> a été supprimé"
           );
        }
      return $this->redirectToRoute('admin_ads_index');
    }
}
