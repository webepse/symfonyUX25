<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\TooManyLoginAttemptsAuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class AdminAccountController extends AbstractController
{
    /**
     * Permet de se connecter à l'admin
     * @param AuthenticationUtils $utils
     * @return Response
     */
    #[Route('/admin/login', name: 'admin_account_login')]
    public function index(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        $loginError =  null;

        if($error instanceof TooManyLoginAttemptsAuthenticationException)
        {
            $loginError = "Trop de tentatives de connexion, Réssayer plus tard";
        }

        $user = $this->getUser();
        if($user)
        {
            if(in_array('ROLE_ADMIN', $user->getRoles()))
            {
                return $this->redirectToRoute("admin_dashboard_index");
            }
        }

        return $this->render('admin/account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username,
            'loginError' => $loginError
        ]);
    }

    /**
     * Permet de faire la déconnexion
     * @return Void
     */
    #[Route('/admin/logout', name: 'admin_account_logout')]
    public function logout(): Void
    {}

    /**
     * Permet d'afficher la page tableau de bord
     * @return Response
     */
    #[Route('/admin/', name: 'admin_dashboard_index')]
    public function dashboard(): Response
    {
        return $this->render("admin/account/dashboard.html.twig");
    }
}
