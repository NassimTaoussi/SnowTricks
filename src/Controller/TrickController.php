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
use App\Service\TrickManager;

class TrickController extends AbstractController
{

    const COMMENTS_DISPLAY_STARTING = 5;
    const COMMENTS_PER_LOADING = 5;

    #[Route('/addTrick', name: 'add_trick')]
    #[IsGranted('ROLE_USER')]
    public function addTrick(Request $request, TrickManager $trickManager): Response
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $trickManager->add($trick);
            $this->addFlash('success','Vous venez de créé un nouveau trick');
            return $this->redirectToRoute('home');
        }

        $trick->getPhotos()->clear();

        

        return $this->render('trick/addTrickNew.html.twig', ['form' => $form]);
    }

    #[Route('editTrick/{id}', name: 'edit_trick')]
    public function editTrick(
        Trick $trick,
        TrickManager $trickManager, 
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
            $trickManager->update($trick);

            foreach($trick->getVideos() as $video) 
        {
            if($video->getLink() === null) 
            {
                $trick->removeVideo($video);
                continue;
            }
            $url = $video->getLink();
            parse_str( parse_url( $url, PHP_URL_QUERY ), $urlId );            
            $video->setLink($urlId['v']);
            $video->setTrick($trick);
            $entityManager->persist($video);
        }

            $this->addFlash('success','Vous venez de mettre à jour ce trick');
            return $this->redirectToRoute('home');
        }

        return $this->render('trick/editTrickNew.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
        ]);
    }

    #[Route('deleteTrick/{id}', name: 'delete_trick')]
    public function deleteTrick($id, TrickRepository $trickRepository) : Response 
    {
        $trickRepository->deleteTrick($id);
        return $this->redirectToRoute('home');
        $this->addFlash('success', 'Le trick a bien été supprimer.');
    }

    #[Route('trick/{slug}', name:'show_trick')]
    public function showTrick(Trick $trick, CommentRepository $commentRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        dump($trick);
        $totalAllComments = $commentRepository->countAllComments($trick);

        $commentsToDisplay = $commentRepository->getFirstComments(self::COMMENTS_DISPLAY_STARTING, $trick);

        /** @var User $user */
        $user = $this->getUser();

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid())
        {
            $comment->setAuthor($user);
            $comment->setCreatedAt(new \DateTimeImmutable('now'));
            $comment->setTrick($trick);

            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('trick/showTrickNew.html.twig', [
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
