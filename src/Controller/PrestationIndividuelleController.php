<?php

namespace App\Controller;

use App\Repository\PrestationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PrestationIndividuelleController extends AbstractController
{
    #[Route('/prestation/{id}', name: 'app_prestation_show')]
    public function show($id, PrestationRepository $prestationRepository): Response
    {
        $prestation = $prestationRepository->find($id);

        if (!$prestation) {
            throw $this->createNotFoundException('Prestation non trouvée pour l\'id ' . $id);
        }

        return $this->render('prestation_individuelle/index.html.twig', [
            'prestation' => $prestation,
        ]);
    }
}
