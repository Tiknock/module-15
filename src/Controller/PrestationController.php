<?php

namespace App\Controller;

use App\Entity\Prestation;
use App\Form\PrestationType;
use App\Repository\PrestationRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PrestationController extends AbstractController
{
    #[Route('/prestation', name: 'app_prestation')]
    public function index(PrestationRepository $prestationRepository, UserRepository $userRepository): Response
    {
        $prestations = $prestationRepository->findPrestationsWithLimitedNumber(10);
        $lastUser = $userRepository->findOneBy([], ['id' => 'DESC']); // Récupère le dernier utilisateur selon l'ID (supposant que l'ID est auto-incrémenté)
        return $this->render('prestation/index.html.twig', [
            'prestations' => $prestations,
            'lastUser' => $lastUser,
        ]);
    }

    #[Route('/ajout', name: 'app_ajout')]
    #[Route('/modifier/{id}', name: 'app_modifier')]
    public function editer(Request $request, PrestationRepository $prestationRepository, EntityManagerInterface $entityManager, int $id = null): Response
    {
        if ($request->attributes->get('_route') == 'app_ajout') {
            $prestation = new Prestation();
        } else {
            $prestation = $prestationRepository->find($id);

            // Vérification si la prestation existe
            if (!$prestation) {
                throw $this->createNotFoundException('La prestation n\'existe pas');
            }

            // Vérification si l'utilisateur actuel est le propriétaire de la prestation
            if ($request->attributes->get('_route') == 'app_modifier' && $prestation->getUser() !== $this->getUser()) {
                throw new AccessDeniedException('Vous n\'avez pas le droit de modifier cette prestation');
            }
        }

        $prestation->setDateCreation(new DateTime());
        $prestation->setUser($this->getUser());
        $form = $this->createForm(PrestationType::class, $prestation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prestation);
            $entityManager->flush();

            if ($request->attributes->get('_route') == 'app_ajout') {
                $this->addFlash(
                    'success',
                    'Vous avez bien ajouté une prestation !'
                );
            } else {
                $this->addFlash(
                    'success',
                    'Vous avez bien modifié votre prestation !'
                );
            }

            return $this->redirectToRoute('app_prestation');
        }

        return $this->render('editer/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/supprimer/{id}', name: 'app_supprimer')]
    public function supprimer(PrestationRepository $prestationRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $prestation = $prestationRepository->find($id);
    
        // Vérification si la prestation existe
        if (!$prestation) {
            throw $this->createNotFoundException('La prestation n\'existe pas');
        }
    
        // Vérification si l'utilisateur actuel est le propriétaire de la prestation
        if ($prestation->getUser() !== $this->getUser()) {
            throw new AccessDeniedException('Vous n\'avez pas le droit de supprimer cette prestation');
        }
    
        $entityManager->remove($prestation);
        $entityManager->flush();
    
        $this->addFlash(
            'success',
            'La prestation a bien été supprimée'
        );
    
        return $this->redirectToRoute('app_prestation');
    }
    
}
