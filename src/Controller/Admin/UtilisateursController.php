<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateursController extends AbstractController
{
    #[Route('/admin/utilisateurs', name: 'app_admin_utilisateurs')]
    public function index(UserRepository $userRepository): Response
    {

        $utilisateurs = $userRepository->findBy([], ['nom' => 'asc']);

        return $this->render('admin/utilisateurs/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }
}
