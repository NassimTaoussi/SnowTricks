<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Entity\Comment;
use App\Form\CommentType;


class TrickController extends AbstractController
{

    const COMMENTS_DISPLAY_STARTING = 5;
    const COMMENTS_PER_LOADING = 5;

    #[Route('/addTrick', name: 'add_trick')]
    #[IsGranted('ROLE_USER')]
    public function addTrick(
        Request $request, 
        EntityManagerInterface $entityManager, 
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads')]
        string $uploadsDir
         ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        $trick = new Trick();
        $trick->setAuthor($user);
        $trick->setCreatedAt(new \DateTimeImmutable('now'));
        $trick->setUpdatedAt(new \DateTimeImmutable('now'));

        $form = $this->createForm(TrickType::class, $trick)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            
            foreach($trick->getPhotos() as $photo) {
                if($photo->getFile() === null) 
                {
                    $trick->removePhoto($photo);
                    continue;
                }
                $photo->setName(Uuid::v4() . "." . $photo->getFile()->guessClientExtension());
                $photo->getFile()->move($uploadsDir, $photo->getName());
            }
            
            
            $entityManager->persist($trick);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        $trick->getPhotos()->clear();

        return $this->render('trick/addTrick.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('editTrick/{id}', name: 'edit_trick')]
    public function editTrick(
        Trick $trick, 
        Request $request, 
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads')]
        string $uploadsDir
        ) : Response {

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            foreach($trick->getPhotos() as $photo) {
                if($photo->getFile() === null) 
                {
                    $trick->removePhoto($photo);
                    continue;
                }
                $photo->setName(Uuid::v4() . "." . $photo->getFile()->guessClientExtension());
                $photo->getFile()->move($uploadsDir, $photo->getName());
                
            }


            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        foreach($trick->getPhotos() as $photo) 
        {
            if($photo->getFile() !== null && $photo->getId() === null) 
            {
                $trick->removePhoto($photo);
            }
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
    public function showTrick(Trick $trick, CommentRepository $commentRepository, Request $request, EntityManagerInterface $entityManager): Response
    {

        $totalAllComments = $commentRepository->countAllComments($trick);

        $commentsToDisplay = $commentRepository->getFirstComments(self::COMMENTS_DISPLAY_STARTING, $trick);

        /** @var User $user */
        $user = $this->getUser();

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $comment->setAuthor($user);
            dump($form);
            exit();
            //$comment->setMessage($form->get)

            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('trick/showTrick.html.twig', [
            'trick' => $trick,
            'form' => $form,
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
