<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index( AdRepository $repo)
    {
       // $repo = $this->getDoctrine()->getRepository(Ad::class);
        $ads = $repo->findAll();
        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }

    /**
     * permet de créer une  annonce
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     * @Route("/ads/new" , name="ads_create")
     */
    public  function  create(Request $request , EntityManagerInterface $manager){
        $ad = new Ad();
        $form = $this->createForm(AdType::class ,$ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush(); // niveau BD
            $this->addFlash(
                'success' ,
                "L'annonce <strong>{$ad->getTitle()} </strong> a été bien enregistrée"
            );
            return $this->redirectToRoute('ads_show' ,[
                'slug' => $ad->getSlug()
            ]);
        }
        return  $this->render('ad/new.html.twig' ,
            [
                'form'=> $form->createView()
            ]
        );
    }



    /**
     * permet d'afficher une seule annonce
     * @return Response
     * @Route("/ads/{slug}" , name="ads_show")
     */
    public function  show(Ad $ad){

        //$ad = $repos->findOneBySlug($slug);
        return $this->render('ad/show.html.twig' , [
            'ad' => $ad
        ]);
    }

    /**
     * permet d'editer  une  annonce
     * @return Response
     * @Route("/ads/{slug}/edit" , name="ads_edit")
     */
    public function edit(Request $request , Ad $ad ,  EntityManagerInterface $manager){

        $form = $this->createForm(AdType::class ,$ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush(); // niveau BD
            $this->addFlash(
                'success' ,
                "L'annonce <strong>{$ad->getTitle()} </strong> a été bien modifié"
            );
            return $this->redirectToRoute('ads_show' ,[
                'slug' => $ad->getSlug()
            ]);
        }
        return $this->render('ad/edit.html.twig' , [
            'form'=>$form->createView(),
            'ad'=>$ad
        ]);
    }

}
