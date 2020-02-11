<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController  extends AbstractController
{

    /**
     * @Route("/",name="homepage")
     */
    public function home(){
        
        $eleves = [
             array(
                "nom" =>"bellili",
                "prenom" => "rami" ,
                "age"=>27
            ),
             array(
                "nom" =>"ahmed",
                "prenom" => "ali" ,
                "age"=>20
            ),
           array(
                "nom" =>"mounir",
                "prenom" => "salah" ,
                "age"=>12
            )
        ];
        $response = new Response();
        $response->setContent(json_encode([
            $data = 123,
        ]));

        $response->headers->set('Content-Type', 'application/json');

        return $this->render('home.html.twig', ['eleves' => $eleves]);

    }
}


