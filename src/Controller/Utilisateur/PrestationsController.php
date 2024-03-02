<?php

namespace App\Controller\Utilisateur;

use App\Repository\PrestationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrestationsController extends AbstractController
{
    #[Route('/utilisateur/prestation', name: 'app_utlisateur_prestation')]
    public function index(PrestationRepository $prestationRepository): Response
    {

        $prestations = $prestationRepository->findBy(['user' => $this->getUser()], ['nom' => 'asc']);

        return $this->render('utilisateur/prestations/index.html.twig', [
            'prestations' => $prestations,
        ]);
    }
}
