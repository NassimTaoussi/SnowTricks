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
    #[Route('/', name: 'home')]
    public function index(Request $request, TrickRepository $trickRepository): Response
    {
        $ajax = $request->isXmlHttpRequest();
        $allCountTricks = $trickRepository->countAllTricks();
        $tricks = $trickRepository->findAll();

        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    
}
