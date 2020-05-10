<?php

namespace App\Controller;

use App\Entity\Comment;

use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comments")
     * @param AdRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(CommentRepository $repo)
    {

        return $this->render('admin/comment/index.html.twig', [
            'comments' => $repo->findAll(),
        ]);
    }


    /**
     * permet de modifier un commentaire
     * @Route("/admin/comments/{id}/edit" , name="admin_comments_edit")
     * @param Comment $comment
     * @return Response
     */
    public function edit (Comment $comment , Request $request , EntityManagerInterface $manager){
        $form = $this->createForm(AdminCommentType::class , $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash(
                'success' ,
                "le commentaire a été modifié avec succés"
            );
             return  $this->redirectToRoute("admin_comments");
        }else{
            return $this->render('admin/comment/edit.html.twig' , [
                'comment' => $comment ,
                'form' => $form->createView()
            ]);
        }

    }

    /**
     * permet de supprmier un commentaire
     * @Route("/admin/comments/{id}/delete" , name="admin_comment_delete")
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function delete(Comment $comment  , EntityManagerInterface $manager){
        $manager->remove($comment);
        $manager->flush();
        $this->addFlash(
            'success' ,
            "le commenatire de <strong>{$comment->getAuthor()->getFullName()} </strong> a été supprimé"
        );
        return  $this->redirectToRoute("admin_comments");
    }
}
