<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $encoder ;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);
        $admin_user = new User();
        $admin_user->setFirstName('RAMI')
                   ->setLastName('bellili')
                   ->setEmail('bellilirami@gmail.com')
                   ->setHash($this->encoder->encodePassword($admin_user , '123456789'))
                   ->setPicture('https://randomuser.me/api/portraits/men/78.jpg')
                   ->setIntroduction($faker->sentence())
                   ->setDescription('<p>'. join('</p><p>' , $faker->paragraphs(3)).'</p>')
                   ->addUserRole($adminRole);
        $manager->persist($admin_user);


        //gestion des utilsateurs
        $users = [];
        $genders = ['male' , 'female'];
        for ($i=0 ;$i<=9 ;$i++){
            $user = new User();
            $genre = $faker->randomElement($genders);
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1,99).'.jpg';
            $hash = $this->encoder->encodePassword($user,'password');
            $picture .= ($genre == 'male' ? 'men/' : 'women/') .$pictureId;

            $user->setFirstName($faker->firstName($genre))
           ->setLastName($faker->lastName)
           ->setEmail($faker->email)
           ->setIntroduction($faker->sentence())
           ->setDescription('<p>'. join('</p><p>' , $faker->paragraphs(3)).'</p>')
            ->setHash($hash)
            ->setPicture($picture);
            $manager->persist($user);
            $users[] = $user;
        }
        //gestion des annonces
        for ($i=0 ;$i<=30 ;$i++){

            $ad = new Ad();
            $title = $faker->sentence(5);
            $coverImgae =$faker->imageUrl(640,480);
            $introduction = $faker->paragraph(2);
            $content = '<p>'. join('</p><p>' , $faker->paragraphs(5)).'</p>';
            $user = $users[mt_rand(0,count($users)-1)];
            $ad->setTitle($title)
                ->setCoverImage($coverImgae)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40,200))
                ->setRooms(mt_rand(1,5))
                ->setAuthor($user);
            for($j = 1 ; $j <= mt_rand(2,5) ; $j++){
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);
                $manager->persist($image);
            }
            // $product = new Product();

            //gestion des reservations
            for($j=1 ;$j<= mt_rand(0,10) ; $j++){
                 $booking = new Booking() ;
                 $createdAt =  $faker->dateTimeBetween('-6 months');
                 $startDate = $faker->dateTimeBetween('-3 months');
                 $duration = mt_rand(3 ,10);

                 $endDate =  (clone $startDate)->modify("+ $duration days");
                 $amount = $ad->getPrice() * $duration ;
                 $booker = $users[mt_rand(0 , count($users)-1)];
                 $comment = $faker->paragraph();

                 $booking->setBooker($booker)
                         ->setAd($ad)
                         ->setStartDate($startDate)
                         ->setEndDate($endDate)
                         ->setCreatedAt($createdAt)
                         ->setAmount($amount)
                         ->setComment($comment);

                 $manager->persist($booking);
            }
            $manager->persist($ad);
        }


        $manager->flush();
    }
}
