<?php

namespace App\Controller;

use App\Form\TrickType;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    const TRICKS_DISPLAY_STARTING = 15;
    const TRICKS_PER_LOADING = 5;

    #[Route('/', name: 'home')]
    public function index(TrickRepository $trickRepository): Response
    {
        $totalAllTricks = $trickRepository->countAllTricks();
        $tricksToDisplay = $trickRepository->getFirstTricks(self::TRICKS_DISPLAY_STARTING);

        return $this->render('home/index.html.twig', [
            'totalAllTricks' => $totalAllTricks,
            'tricksToDisplay' => $tricksToDisplay,
            'totalDisplayTricks' => self::TRICKS_DISPLAY_STARTING,
            'tricksPerLoading' => self::TRICKS_PER_LOADING,
        ]);
    }

    #[Route('/getData', name:'get_data', methods: ['POST'])]
    public function loadMoreTricks(Request $request, TrickRepository $trickRepository): Response
    {
        // configuration
        $tricksAlreadyLoaded = $request->get('totalDisplayTricks');
        // selecting posts
        $tricksToDisplay = $trickRepository->getMoreTricks($tricksAlreadyLoaded, self::TRICKS_PER_LOADING);

        return $this->render('trick/elements.html.twig', [
            'tricksToDisplay' => $tricksToDisplay,
        ]);
    }
}
