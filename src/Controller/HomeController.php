<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AvatarFormType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class HomeController extends AbstractController
{
    public const TRICKS_DISPLAY_STARTING = 10;
    public const TRICKS_PER_LOADING = 10;

    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(TrickRepository $trickRepository): Response
    {
        // Récupération du total de l'ensemble des tricks
        $totalAllTricks = $trickRepository->countAllTricks();
        
        // On récupére les 10 premiers tricks à afficher
        $tricksToDisplay = $trickRepository->getFirstTricks(self::TRICKS_DISPLAY_STARTING);

        return $this->render('home/index.html.twig', [
            'totalAllTricks' => $totalAllTricks,
            'tricksToDisplay' => $tricksToDisplay,
            'totalDisplayTricks' => self::TRICKS_DISPLAY_STARTING,
            'tricksPerLoading' => self::TRICKS_PER_LOADING,
        ]);
    }

    #[Route('/getData', name: 'get_data', methods: ['POST'])]
    public function loadMoreTricks(Request $request, TrickRepository $trickRepository): Response
    {
        // On récupère l'ensemble des tricks déjà charger sur la page
        $tricksAlreadyLoaded = $request->get('totalDisplayTricks');
        
        // On récupère d'autre tricks à afficher
        $tricksToDisplay = $trickRepository->getMoreTricks($tricksAlreadyLoaded, self::TRICKS_PER_LOADING);

        return $this->render('trick/elements.html.twig', [
            'tricksToDisplay' => $tricksToDisplay,
        ]);
    }

    #[Route('/editAvatar', name: 'editAvatar', methods: ['POST', 'GET'])]
    #[IsGranted('ROLE_USER')]
    public function editAvatar(
        Request $request,
        EntityManagerInterface $entityManager,
        #[Autowire('%kernel.project_dir%/public/uploads')]
        string $uploadsDir
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(AvatarFormType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['avatar']->getData();
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Uuid::v4($originalFilename) . '.' . $file->guessExtension();
            $file->move($uploadsDir, $newFilename);
            $user->setAvatar($newFilename);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('home/editAvatar.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }
}
