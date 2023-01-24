<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{

    const COMMENTS_DISPLAY_STARTING = 5;
    const COMMENTS_PER_LOADING = 5;

    #[Route('/addTrick', name: 'add_trick')]
    public function addTrick(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        $trick = new Trick();
        $trick->setAuthor($user);
        $trick->setCreatedAt(new \DateTimeImmutable('now'));
        $trick->setUpdatedAt(new \DateTimeImmutable('now'));
        dump($trick);
        exit();

        /*foreach($images as $image){
            // On génère un nouveau nom de fichier
            $fichier = md5(uniqid()).'.'.$image->guessExtension();
            
            // On copie le fichier dans le dossier uploads
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );
            
            // On crée l'image dans la base de données
            $img = new Images();
            $img->setName($fichier);
            $annonce->addImage($img);
        }*/

        $form = $this->createForm(TrickType::class, $trick)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($trick);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('trick/addTrick.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('editTrick/{id}', name: 'edit_trick')]
    public function editTrick(Trick $trick, Request $request, EntityManagerInterface $entityManager) : Response {

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($trick);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('trick/editTrick.html.twig', [
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
    public function showTrick(Trick $trick, CommentRepository $commentRepository): Response
    {

        $totalAllComments = $commentRepository->countAllComments($trick);

        $commentsToDisplay = $commentRepository->getFirstComments(self::COMMENTS_DISPLAY_STARTING, $trick);

        return $this->render('trick/showTrick.html.twig', [
            'trick' => $trick,
            'totalAllComments' => $totalAllComments,
            'commentsToDisplay' => $commentsToDisplay,
            'totalDisplayComments' => self::COMMENTS_DISPLAY_STARTING,
            'commentsPerLoading' => self::COMMENTS_PER_LOADING,
        ]);
    }

    #[Route('/getMoreComments/{id}', name: 'get_more_comment', methods: ['GET'])]
    public function getMoreComments(Trick $trick, Request $request, CommentRepository $commentRepository): Response
    {
        
        // configuration
        $commentsAlreadyLoaded = $request->query->getInt('totalDisplayComments');
        // selecting posts
        $commentsToDisplay = $commentRepository->getMoreComments($commentsAlreadyLoaded, self::COMMENTS_PER_LOADING, $trick);

        return $this->render('comment/elements.html.twig', [
            'commentsToDisplay' => $commentsToDisplay,
        ]);
    }
}
