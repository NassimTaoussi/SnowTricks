<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    const COMMENTS_PER_LOADING = 10;

    #[Route('/getMoreComment', name: 'get_more_comment', methods: ['POST'])]
    public function getMoreComments(Request $request, CommentRepository $commentRepository): Response
    {
        // configuration
        $commentsAlreadyLoaded = $request->get('totalDisplayComments');
        // selecting posts
        $commentsToDisplay = $commentRepository->getMoreComments($commentsAlreadyLoaded, self::COMMENTS_PER_LOADING);

        return $this->render('comment/elements.html.twig', [
            'commentsToDisplay' => $commentsToDisplay,
        ]);
    }
}
