<?php

namespace App\Service ;

use Doctrine\ORM\EntityManagerInterface;

class StatsService {

private $manager ;
public  function __construct(EntityManagerInterface $manager)
{
    $this->manager = $manager ;
}

public function getUsersCount(){
    return $this->manager->createQuery('SELECT count(u) FROM App\Entity\User u')->getSingleScalarResult();
}

public function getAds(){
    return $this->manager->createQuery('SELECT count(a) FROM App\Entity\Ad a')->getSingleScalarResult();
}
public function  getStats(){
    $users = $this->getUsersCount();
    $ads= $this->getAds();
    $bookings= $this->getBooking();
    $comments= $this->getComments();
    return compact('users' , 'ads' , 'bookings' , 'comments');

}
public function getBooking(){
    return $this->manager->createQuery('SELECT count(b) FROM App\Entity\Booking b')->getSingleScalarResult();
}

public function getComments(){
    return $this->manager->createQuery('SELECT count(c) FROM App\Entity\Comment c')->getSingleScalarResult();
}


public  function getStastAds($queryOrder){
    return $this->manager->createQuery(
        'SELECT AVG(c.rating) as note  ,a.title , a.id  , u.firstName , u.lastName , u.picture
                  FROM App\Entity\Comment c
                  JOIN c.ad a
                  JOIN  a.author u
                  GROUP BY a
                  ORDER BY note '.$queryOrder
            )
        ->setMaxResults(5)
        ->getResult();
}
}