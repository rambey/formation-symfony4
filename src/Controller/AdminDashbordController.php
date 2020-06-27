<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashbordController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashbord")
     * @param EntityManagerInterface $manager
     * @param StatsService $statsService
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function index(EntityManagerInterface $manager , StatsService $statsService)
    {

        $bestAds =  $statsService->getStastAds('DESC');
        $worstAds =$statsService->getStastAds('ASC');

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $statsService->getStats(),
            'bestAds' => $bestAds ,
            'worstAds' => $worstAds ,

        ]);
    }
}
