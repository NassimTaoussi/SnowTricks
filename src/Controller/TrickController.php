<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{

    const COMMENTS_DISPLAY_STARTING = 10;

    #[Route('/addTrick', name: 'add_trick')]
    #[Route('editTrick/{id}', name: 'edit_trick')]
    public function addTrick(?Trick $trick, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$trick)
        {
            $trick = new Trick();
        }

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if(!$trick->getId()){
                $entityManager->persist($trick);
            }
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('trick/addTrick.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('deleteTrick/{id}', name: 'delete_trick')]
    public function deleteTrick($id, TrickRepository $trickRepository) : Response 
    {
        $trickRepository->deleteTrick($id);
        return $this->redirectToRoute('home');
        $this->addFlash('success', 'Le trick a bien été supprimer.');
    }

    #[Route('trick/{id}', name:'show_trick')]
    public function showTrick(Trick $trick): Response
    {
        /*dump($trick);
        exit();*/
        return $this->render('trick/showTrick.html.twig', [
            'trick' => $trick,
        ]);
    }
}
