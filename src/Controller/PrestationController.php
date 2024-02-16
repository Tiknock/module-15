<?php

namespace App\Controller;

use App\Entity\Prestation;
use App\Form\PrestationType;
use App\Repository\PrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PrestationController extends AbstractController
{
    #[Route('/prestation', name: 'app_prestation')]
    public function index(PrestationRepository $prestationRepository): Response

    {
        $prestations = $prestationRepository->findPrestationsWithLimitedNumber(10);

        return $this->render('prestation/index.html.twig', [
            'prestations' => $prestations,
        ]);
    }

    #[Route('/ajout', name: 'app_ajout')]
    public function ajout(Request $request,  EntityManagerInterface $entityManager): Response

    {
        $prestation = new Prestation();
        $prestation->setDateCreation(new DateTime());
        $form = $this->createForm(PrestationType::class, $prestation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prestation);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Vous avez bien ajouté une prestation !'
            );

            return $this->redirectToRoute('app_prestation');
        }

        return $this->render('ajout/index.html.twig', [
            'form' => $form
        ]);
    }
}
